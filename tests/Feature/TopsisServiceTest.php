<?php

namespace Tests\Feature;

use App\Models\HasilTopsis;
use App\Models\Kriteria;
use App\Models\Siswa;
use App\Models\SubKriteria;
use App\Models\User;
use App\Services\TopsisService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopsisServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TopsisService;
        $this->seedDummyData();
    }

    private function seedDummyData()
    {
        // Kriteria
        $kriterias = [
            ['kode' => 'C1', 'nama_kriteria' => 'Pekerjaan Ayah', 'atribut' => 'Benefit', 'bobot' => 5],
            ['kode' => 'C2', 'nama_kriteria' => 'Penghasilan Ayah', 'atribut' => 'Benefit', 'bobot' => 4],
            ['kode' => 'C3', 'nama_kriteria' => 'Pekerjaan Ibu', 'atribut' => 'Benefit', 'bobot' => 5],
            ['kode' => 'C4', 'nama_kriteria' => 'Penghasilan Ibu', 'atribut' => 'Benefit', 'bobot' => 4],
            ['kode' => 'C5', 'nama_kriteria' => 'Jumlah Tanggungan', 'atribut' => 'Benefit', 'bobot' => 4],
            ['kode' => 'C6', 'nama_kriteria' => 'Status Siswa', 'atribut' => 'Benefit', 'bobot' => 5],
        ];

        foreach ($kriterias as $k) {
            $kr = Kriteria::create($k);
        }

        $user = User::factory()->create(['role' => 'wali_kelas']);

        // Siswa 1 (Submitted, Nilai Buruk untuk Prioritas)
        // All Benefit = 1 (Kecuali C5 = 5) -> Kurang Prioritas
        $this->createSiswa($user->id, 'SW-001', 'Siswa A', 'submitted', [
            'C1' => 1, 'C2' => 1, 'C3' => 1, 'C4' => 1, 'C5' => 5, 'C6' => 1,
        ]);

        // Siswa 2 (Submitted, Nilai Bagus untuk Prioritas)
        // All Benefit = 5 (Kecuali C5 = 1) -> Sangat Prioritas
        $this->createSiswa($user->id, 'SW-002', 'Siswa B', 'submitted', [
            'C1' => 5, 'C2' => 5, 'C3' => 5, 'C4' => 5, 'C5' => 1, 'C6' => 5,
        ]);

        // Siswa 3 (Draft, Tidak Boleh Ikut Dihitung)
        $this->createSiswa($user->id, 'SW-003', 'Siswa C (Draft)', 'draft', [
            'C1' => 3, 'C2' => 3, 'C3' => 3, 'C4' => 3, 'C5' => 3, 'C6' => 3,
        ]);
    }

    private function createSiswa($userId, $kode, $nama, $status, $nilai)
    {
        $siswa = Siswa::create([
            'user_id' => $userId,
            'kode_siswa' => $kode,
            'nama_siswa' => $nama,
            'kelas' => '10A',
            'periode' => '2026/2027',
            'status_data' => $status,
        ]);
        foreach ($nilai as $kodeKr => $val) {
            $kr = Kriteria::where('kode', $kodeKr)->first();
            $sub = SubKriteria::create([
                'kriteria_id' => $kr->id,
                'nama_sub_kriteria' => 'Sub '.$kodeKr.' Val '.$val,
                'nilai' => $val,
            ]);
            $siswa->penilaians()->create([
                'kriteria_id' => $kr->id,
                'sub_kriteria_id' => $sub->id,
            ]);
        }

        return $siswa;
    }

    public function test_matriks_keputusan_terbentuk_dan_draft_diabaikan()
    {
        $data = $this->service->calculate(false);
        $decisionMatrix = $data['decisionMatrix'];

        // Hanya 2 siswa yang submitted yang dihitung
        $this->assertCount(2, $decisionMatrix);

        $siswaA = Siswa::where('kode_siswa', 'SW-001')->first();
        $siswaB = Siswa::where('kode_siswa', 'SW-002')->first();

        $this->assertArrayHasKey($siswaA->id, $decisionMatrix);
        $this->assertArrayHasKey($siswaB->id, $decisionMatrix);

        // Pastikan nilai Matriks Keputusan Sesuai
        $this->assertEquals(1, $decisionMatrix[$siswaA->id]['C1']);
        $this->assertEquals(1, $decisionMatrix[$siswaB->id]['C5']);
    }

    public function test_nilai_preferensi_berhasil_dihitung_dan_tersimpan()
    {
        $this->service->calculate(true);

        $this->assertDatabaseCount('hasil_topsis', 2);

        $siswaA = Siswa::where('kode_siswa', 'SW-001')->first();
        $hasilA = HasilTopsis::where('siswa_id', $siswaA->id)->first();

        $this->assertNotNull($hasilA);
        $this->assertTrue($hasilA->nilai_preferensi >= 0 && $hasilA->nilai_preferensi <= 1);
        $this->assertNotNull($hasilA->d_plus);
        $this->assertNotNull($hasilA->d_minus);
    }

    public function test_ranking_terurut_menurun()
    {
        $data = $this->service->calculate(true);
        $rankings = $data['rankings'];
        $preferences = $data['preferences'];

        $siswaA = Siswa::where('kode_siswa', 'SW-001')->first(); // Harusnya rank 2 (Kurang Prioritas)
        $siswaB = Siswa::where('kode_siswa', 'SW-002')->first(); // Harusnya rank 1 (Prioritas)

        $this->assertEquals(2, $rankings[$siswaA->id]);
        $this->assertEquals(1, $rankings[$siswaB->id]);

        $this->assertTrue($preferences[$siswaB->id] > $preferences[$siswaA->id]);
    }

    public function test_cost_criterion_uses_minimum_as_positive_ideal(): void
    {
        Kriteria::where('kode', 'C5')->update(['atribut' => 'Cost']);
        $data = $this->service->calculate(false);
        $best = Siswa::where('kode_siswa', 'SW-002')->firstOrFail();
        $worst = Siswa::where('kode_siswa', 'SW-001')->firstOrFail();
        $this->assertEqualsWithDelta($data['weightedMatrix'][$best->id]['C5'], $data['positiveIdeal']['C5'], 1e-10);
        $this->assertEqualsWithDelta($data['weightedMatrix'][$worst->id]['C5'], $data['negativeIdeal']['C5'], 1e-10);
        $this->assertEqualsWithDelta(1, $data['preferences'][$best->id], 1e-10);
        $this->assertEqualsWithDelta(0, $data['preferences'][$worst->id], 1e-10);
    }

    public function test_filtered_recalculation_preserves_other_class_and_period(): void
    {
        $this->service->calculate(true);
        $siswa = Siswa::where('kode_siswa', 'SW-002')->firstOrFail();
        $siswa->update(['kelas' => '11A', 'periode' => '2027/2028']);
        $before = HasilTopsis::where('siswa_id', $siswa->id)->firstOrFail()->toArray();
        $data = $this->service->calculate(true, '10A', '2026/2027');
        $this->assertCount(1, $data['rankings']);
        $this->assertSame($before, HasilTopsis::where('siswa_id', $siswa->id)->firstOrFail()->toArray());
        $this->assertDatabaseCount('hasil_topsis', 2);
    }

    public function test_incomplete_assessment_does_not_replace_saved_results(): void
    {
        $this->service->calculate(true);
        Siswa::where('kode_siswa', 'SW-001')->firstOrFail()->penilaians()->delete();
        try {
            $this->service->calculate(true);
            $this->fail('Incomplete assessments must be rejected.');
        } catch (Exception $e) {
            $this->assertStringContainsString('belum lengkap', $e->getMessage());
        }
        $this->assertDatabaseCount('hasil_topsis', 2);
    }

    public function test_identical_and_zero_values_produce_finite_deterministic_rankings(): void
    {
        SubKriteria::query()->update(['nilai' => 0]);
        $data = $this->service->calculate(false);
        $this->assertSame([0, 0], array_values($data['preferences']));
        $this->assertSame([1, 2], array_values($data['rankings']));
        $this->assertDatabaseCount('hasil_topsis', 0);
    }

    public function test_empty_selection_is_rejected(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Tidak ada data siswa');
        $this->service->calculate(false, 'Tidak Ada');
    }

    public function test_no_criteria_is_rejected(): void
    {
        Kriteria::query()->delete();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Belum ada data kriteria');
        $this->service->calculate(false);
    }
}
