<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $kriterias = [
            ['kode' => 'C1', 'nama_kriteria' => 'Pekerjaan Ayah', 'atribut' => 'Benefit', 'bobot' => 5, 'urutan' => 1],
            ['kode' => 'C2', 'nama_kriteria' => 'Penghasilan Ayah', 'atribut' => 'Benefit', 'bobot' => 4, 'urutan' => 2],
            ['kode' => 'C3', 'nama_kriteria' => 'Pekerjaan Ibu', 'atribut' => 'Benefit', 'bobot' => 5, 'urutan' => 3],
            ['kode' => 'C4', 'nama_kriteria' => 'Penghasilan Ibu', 'atribut' => 'Benefit', 'bobot' => 4, 'urutan' => 4],
            ['kode' => 'C5', 'nama_kriteria' => 'Jumlah Tanggungan Keluarga', 'atribut' => 'Benefit', 'bobot' => 4, 'urutan' => 5],
            ['kode' => 'C6', 'nama_kriteria' => 'Status Siswa', 'atribut' => 'Benefit', 'bobot' => 5, 'urutan' => 6],
        ];

        foreach ($kriterias as $k) {
            Kriteria::create($k);
        }
    }
}
