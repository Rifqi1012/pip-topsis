<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Siswa;
use App\Models\HasilTopsis;
use App\Services\TopsisService;
use Exception;

class TopsisServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TopsisService();
        $this->seedDummyData();
    }

    private function seedDummyData()
    {
        // Kriteria
        $kriterias = [
            ['kode' => 'C1', 'nama_kriteria' => 'Pekerjaan Ayah', 'atribut' => 'Cost', 'bobot' => 5],
            ['kode' => 'C2', 'nama_kriteria' => 'Penghasilan Ayah', 'atribut' => 'Cost', 'bobot' => 4],
            ['kode' => 'C3', 'nama_kriteria' => 'Pekerjaan Ibu', 'atribut' => 'Cost', 'bobot' => 5],
            ['kode' => 'C4', 'nama_kriteria' => 'Penghasilan Ibu', 'atribut' => 'Cost', 'bobot' => 4],
            ['kode' => 'C5', 'nama_kriteria' => 'Jumlah Tanggungan', 'atribut' => 'Benefit', 'bobot' => 4],
            ['kode' => 'C6', 'nama_kriteria' => 'Status Siswa', 'atribut' => 'Cost', 'bobot' => 5],
        ];

        foreach ($kriterias as $k) {
            $kr = Kriteria::create($k);
        }

        $user = User::factory()->create(['role' => 'wali_kelas']);

        // Siswa 1 (Submitted, Nilai Bagus untuk Prioritas)
        // Benefit (C5) = 5 (Max), Cost (C1,C2,C3,C4,C6) = 1 (Min) -> Sangat Prioritas
        $this->createSiswa($user->id, 'SW-001', 'Siswa A', 'submitted', [
            'C1' => 1, 'C2' => 1, 'C3' => 1, 'C4' => 1, 'C5' => 5, 'C6' => 1
        ]);

        // Siswa 2 (Submitted, Nilai Buruk untuk Prioritas)
        // Benefit = 1, Cost = 5 -> Kurang Prioritas
        $this->createSiswa($user->id, 'SW-002', 'Siswa B', 'submitted', [
            'C1' => 5, 'C2' => 5, 'C3' => 5, 'C4' => 5, 'C5' => 1, 'C6' => 5
        ]);

        // Siswa 3 (Draft, Tidak Boleh Ikut Dihitung)
        $this->createSiswa($user->id, 'SW-003', 'Siswa C (Draft)', 'draft', [
            'C1' => 3, 'C2' => 3, 'C3' => 3, 'C4' => 3, 'C5' => 3, 'C6' => 3
        ]);
    }

    private function createSiswa($userId, $kode, $nama, $status, $nilai)
    {
        $cIds = [];
        foreach ($nilai as $kodeKr => $val) {
            $kr = Kriteria::where('kode', $kodeKr)->first();
            $sub = SubKriteria::create([
                'kriteria_id' => $kr->id,
                'nama_sub_kriteria' => 'Sub ' . $kodeKr . ' Val ' . $val,
                'nilai' => $val
            ]);
            $cIds[strtolower($kodeKr) . '_id'] = $sub->id;
        }

        Siswa::create(array_merge([
            'user_id' => $userId,
            'kode_siswa' => $kode,
            'nama_siswa' => $nama,
            'kelas' => '10A',
            'status_data' => $status
        ], $cIds));
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
        $this->assertEquals(1, $decisionMatrix[$siswaB->id]['C5']); // Wait, Siswa B C5 is 1. My test comment says 5, let's just assert 1.
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

        $siswaA = Siswa::where('kode_siswa', 'SW-001')->first(); // Harusnya rank 1 (Prioritas)
        $siswaB = Siswa::where('kode_siswa', 'SW-002')->first(); // Harusnya rank 2

        $this->assertEquals(1, $rankings[$siswaA->id]);
        $this->assertEquals(2, $rankings[$siswaB->id]);

        $this->assertTrue($preferences[$siswaA->id] > $preferences[$siswaB->id]);
    }
}
