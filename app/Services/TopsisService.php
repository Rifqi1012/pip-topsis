<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Kriteria;
use App\Models\HasilTopsis;
use Illuminate\Support\Facades\DB;
use Exception;

class TopsisService
{
    /**
     * @return array<string, mixed>
     * @throws Exception
     */
    /**
     * @param bool $saveToDb
     * @return array<string, mixed>
     * @throws Exception
     */
    public function calculate(bool $saveToDb = true): array
    {
        // 1. Validasi Pra-kondisi
        $kriterias = Kriteria::orderBy('kode', 'asc')->get();
        if ($kriterias->count() < 6) {
            throw new Exception("Data kriteria belum lengkap (minimal C1-C6).");
        }
        
        foreach ($kriterias as $k) {
            if (!$k->bobot || !$k->atribut) {
                throw new Exception("Bobot atau Atribut pada kriteria {$k->kode} belum diatur.");
            }
        }

        $siswas = Siswa::with(['c1.kriteria', 'c2.kriteria', 'c3.kriteria', 'c4.kriteria', 'c5.kriteria', 'c6.kriteria'])
            ->where('status_data', 'submitted')
            ->get();

        if ($siswas->isEmpty()) {
            throw new Exception("Tidak ada data siswa dengan status 'submitted' untuk dihitung.");
        }

        foreach ($siswas as $siswa) {
            if (!$siswa->c1 || !$siswa->c2 || !$siswa->c3 || !$siswa->c4 || !$siswa->c5 || !$siswa->c6) {
                throw new Exception("Data penilaian siswa {$siswa->nama_siswa} belum lengkap.");
            }
        }

        // 2. Matriks Keputusan (X)
        $decisionMatrix = [];
        $criteriaValues = [
            'C1' => [], 'C2' => [], 'C3' => [], 
            'C4' => [], 'C5' => [], 'C6' => []
        ];

        foreach ($siswas as $siswa) {
            $x = [
                'C1' => $siswa->c1->nilai,
                'C2' => $siswa->c2->nilai,
                'C3' => $siswa->c3->nilai,
                'C4' => $siswa->c4->nilai,
                'C5' => $siswa->c5->nilai,
                'C6' => $siswa->c6->nilai,
            ];
            $decisionMatrix[$siswa->id] = $x;

            foreach ($x as $c => $val) {
                $criteriaValues[$c][] = $val;
            }
        }

        // Pembagi Normalisasi per Kriteria: akar(Sigma(x^2))
        $divisors = [];
        foreach ($criteriaValues as $c => $values) {
            $sumSq = collect($values)->map(fn($v) => pow($v, 2))->sum();
            $divisors[$c] = sqrt($sumSq);
        }

        // 3. Matriks Normalisasi (R) & 4. Matriks Normalisasi Terbobot (Y)
        $normalizedMatrix = [];
        $weightedMatrix = [];
        $weightedCriteriaValues = [
            'C1' => [], 'C2' => [], 'C3' => [], 
            'C4' => [], 'C5' => [], 'C6' => []
        ];

        // Mapping bobot & atribut
        $weights = $kriterias->pluck('bobot', 'kode')->toArray();
        $attributes = $kriterias->pluck('atribut', 'kode')->toArray();

        foreach ($decisionMatrix as $id => $x) {
            $r = [];
            $y = [];
            foreach ($x as $c => $val) {
                $norm = $divisors[$c] == 0 ? 0 : $val / $divisors[$c];
                $r[$c] = $norm;
                
                $weight = $weights[$c] ?? 0;
                $weighted = $norm * $weight;
                $y[$c] = $weighted;
                
                $weightedCriteriaValues[$c][] = $weighted;
            }
            $normalizedMatrix[$id] = $r;
            $weightedMatrix[$id] = $y;
        }

        // 5 & 6. Solusi Ideal Positif (A+) dan Negatif (A-)
        $positiveIdeal = [];
        $negativeIdeal = [];

        foreach ($weightedCriteriaValues as $c => $values) {
            $attr = $attributes[$c] ?? 'Benefit';
            if ($attr === 'Benefit') {
                $positiveIdeal[$c] = collect($values)->max();
                $negativeIdeal[$c] = collect($values)->min();
            } else { // Cost
                $positiveIdeal[$c] = collect($values)->min();
                $negativeIdeal[$c] = collect($values)->max();
            }
        }

        // 7 & 8. Jarak Solusi (D+ dan D-) & 9. Nilai Preferensi (V)
        $dPlus = [];
        $dMinus = [];
        $preferences = [];

        foreach ($weightedMatrix as $id => $y) {
            $sumPlusSq = 0;
            $sumMinusSq = 0;

            foreach ($y as $c => $val) {
                $sumPlusSq += pow($val - $positiveIdeal[$c], 2);
                $sumMinusSq += pow($val - $negativeIdeal[$c], 2);
            }

            $dp = sqrt($sumPlusSq);
            $dm = sqrt($sumMinusSq);

            $dPlus[$id] = $dp;
            $dMinus[$id] = $dm;

            $v = ($dp + $dm) == 0 ? 0 : $dm / ($dp + $dm);
            $preferences[$id] = $v;
        }

        // 10. Ranking
        arsort($preferences); // Sort descending based on preference values
        
        $rankings = [];
        $rank = 1;
        $dbData = [];
        $now = now();

        foreach ($preferences as $id => $v) {
            $rankings[$id] = $rank;
            $dbData[] = [
                'siswa_id' => $id,
                'nilai_preferensi' => $v,
                'd_plus' => $dPlus[$id],
                'd_minus' => $dMinus[$id],
                'ranking' => $rank,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $rank++;
        }

        // Transaction untuk menyimpan hasil
        if ($saveToDb) {
            DB::transaction(function () use ($dbData) {
                HasilTopsis::query()->delete();
                HasilTopsis::insert($dbData);
            });
        }

        // Mapping Data Siswa
        $siswaData = $siswas->keyBy('id');

        return [
            'siswas' => $siswaData,
            'kriterias' => $kriterias,
            'decisionMatrix' => $decisionMatrix,
            'normalizedMatrix' => $normalizedMatrix,
            'weightedMatrix' => $weightedMatrix,
            'positiveIdeal' => $positiveIdeal,
            'negativeIdeal' => $negativeIdeal,
            'dPlus' => $dPlus,
            'dMinus' => $dMinus,
            'preferences' => $preferences,
            'rankings' => $rankings,
        ];
    }
}
