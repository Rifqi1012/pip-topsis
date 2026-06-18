<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\User;

class DummySiswaSeeder extends Seeder
{
    public function run(): void
    {
        $dummyData = [
            ['kode_siswa' => 'SW-0001', 'nama_siswa' => 'Abid Aqilla Pranaja Hidayat', 'kelas' => '10A', 'nilai' => [5, 3, 5, 5, 2, 2]],
            ['kode_siswa' => 'SW-0002', 'nama_siswa' => 'Abidzar Al Ghifari', 'kelas' => '10A', 'nilai' => [2, 3, 5, 5, 2, 1]],
            ['kode_siswa' => 'SW-0003', 'nama_siswa' => 'Abizar Qiyamullail', 'kelas' => '10A', 'nilai' => [1, 2, 5, 5, 2, 1]],
            ['kode_siswa' => 'SW-0004', 'nama_siswa' => 'Achmad Akbar Nur Saepuloh', 'kelas' => '10A', 'nilai' => [2, 2, 5, 5, 2, 1]],
            ['kode_siswa' => 'SW-0005', 'nama_siswa' => 'Acitya Kukami Putriarti Purwanto', 'kelas' => '10A', 'nilai' => [5, 2, 3, 2, 2, 1]],
            ['kode_siswa' => 'SW-0006', 'nama_siswa' => 'Adeeva Shakila Nurhepzibah', 'kelas' => '10A', 'nilai' => [3, 4, 2, 5, 1, 1]],
            ['kode_siswa' => 'SW-0007', 'nama_siswa' => 'Adelia Amalia', 'kelas' => '10A', 'nilai' => [2, 4, 5, 5, 4, 2]],
            ['kode_siswa' => 'SW-0008', 'nama_siswa' => 'Adelia Nurul Syifa', 'kelas' => '10A', 'nilai' => [2, 3, 5, 5, 5, 2]],
            ['kode_siswa' => 'SW-0009', 'nama_siswa' => 'Adelio Gibran Nugraha', 'kelas' => '10A', 'nilai' => [2, 2, 2, 2, 4, 2]],
            ['kode_siswa' => 'SW-0010', 'nama_siswa' => 'Adila Nisa Ardani', 'kelas' => '10A', 'nilai' => [1, 2, 5, 5, 1, 1]],
        ];

        $user = User::where('role', 'wali_kelas')->first();
        if (!$user) {
            $user = User::factory()->create(['role' => 'wali_kelas', 'name' => 'Wali Kelas', 'email' => 'wali@sekolah.com']);
        }

        $kriterias = Kriteria::orderBy('kode', 'asc')->get();

        foreach ($dummyData as $data) {
            $cIds = [];
            foreach ($kriterias as $index => $kriteria) {
                $nilaiTarget = $data['nilai'][$index];
                
                $sub = SubKriteria::where('kriteria_id', $kriteria->id)
                                  ->where('nilai', $nilaiTarget)
                                  ->first();
                                  
                if ($sub) {
                    $field = strtolower($kriteria->kode) . '_id';
                    $cIds[$field] = $sub->id;
                }
            }

            if (count($cIds) === 6) {
                Siswa::updateOrCreate(
                    ['kode_siswa' => $data['kode_siswa']],
                    array_merge([
                        'user_id' => $user->id,
                        'nama_siswa' => $data['nama_siswa'],
                        'kelas' => $data['kelas'],
                        'status_data' => 'submitted',
                    ], $cIds)
                );
            }
        }
    }
}
