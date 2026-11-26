<?php

namespace App\Services;

use App\Models\HasilTopsis;
use App\Models\Kriteria;
use App\Models\Siswa;
use Exception;
use Illuminate\Support\Facades\DB;

class TopsisService
{
    /**
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function calculate(bool $saveToDb = true, ?string $kelas = null, ?string $periode = null): array
    {
        // 1. Validasi Pra-kondisi (Dinamis mengikuti seluruh kriteria di database)
        $kriterias = Kriteria::orderBy('urutan', 'asc')->orderBy('id', 'asc')->get();
        if ($kriterias->isEmpty()) {
            throw new Exception('Belum ada data kriteria yang tersedia. Silakan tambahkan kriteria terlebih dahulu.');
        }

        foreach ($kriterias as $k) {
            if ($k->bobot < 1 || ! in_array($k->atribut, ['Benefit', 'Cost'])) {
                throw new Exception("Bobot atau Atribut pada kriteria {$k->kode} belum diatur.");
            }
        }

        $query = Siswa::with(['penilaians.subKriteria'])
            ->where('status_data', 'submitted');

        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        if ($periode) {
            $query->where('periode', $periode);
        }

        $siswas = $query->orderBy('id')->get();

        if ($siswas->isEmpty()) {
            $msg = "Tidak ada data siswa dengan status 'submitted'";
            if ($kelas) {
                $msg .= " di Kelas {$kelas}";
            }
            if ($periode) {
                $msg .= " (Periode: {$periode})";
            }
            $msg .= ' untuk diproses. Pastikan data siswa di kelas dan periode tersebut sudah ada dan berstatus submitted.';
            throw new Exception($msg);
        }

        // 2. Matriks Keputusan (X)
        $decisionMatrix = [];
        $criteriaValues = [];
        foreach ($kriterias as $k) {
            $criteriaValues[$k->kode] = [];
        }

        foreach ($siswas as $siswa) {
            $penilaianByKriteria = $siswa->penilaians->keyBy('kriteria_id');
            $x = [];

            foreach ($kriterias as $k) {
                $penilaian = $penilaianByKriteria->get($k->id);
                if (! $penilaian || ! $penilaian->subKriteria || $penilaian->subKriteria->kriteria_id !== $k->id) {
                    throw new Exception("Data penilaian siswa {$siswa->nama_siswa} belum lengkap pada kriteria {$k->nama_kriteria} ({$k->kode}).");
                }
                $val = $penilaian->subKriteria->nilai;
                $x[$k->kode] = $val;
                $criteriaValues[$k->kode][] = $val;
            }

            $decisionMatrix[$siswa->id] = $x;
        }

        // Pembagi Normalisasi per Kriteria: akar(Sigma(x^2))
        $divisors = [];
        foreach ($criteriaValues as $c => $values) {
            $sumSq = collect($values)->map(fn ($v) => pow($v, 2))->sum();
            $divisors[$c] = sqrt($sumSq);
        }

        // 3. Matriks Normalisasi (R) & 4. Matriks Normalisasi Terbobot (Y)
        $normalizedMatrix = [];
        $weightedMatrix = [];
        $weightedCriteriaValues = [];
        foreach ($kriterias as $k) {
            $weightedCriteriaValues[$k->kode] = [];
        }

        $weights = $kriterias->pluck('bobot', 'kode')->toArray();

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

        foreach ($kriterias as $k) {
            $values = $weightedCriteriaValues[$k->kode];
            $positiveIdeal[$k->kode] = $k->atribut === 'Cost' ? min($values) : max($values);
            $negativeIdeal[$k->kode] = $k->atribut === 'Cost' ? max($values) : min($values);
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
        arsort($preferences);

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
            DB::transaction(function () use ($dbData, $kelas, $periode) {
                $q = HasilTopsis::query();

                if ($kelas || $periode) {
                    $q->whereHas('siswa', function ($sq) use ($kelas, $periode) {
                        if ($kelas) {
                            $sq->where('kelas', $kelas);
                        }
                        if ($periode) {
                            $sq->where('periode', $periode);
                        }
                    });
                }

                $q->delete();

                if (! empty($dbData)) {
                    HasilTopsis::insert($dbData);
                }
            });
        }

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
