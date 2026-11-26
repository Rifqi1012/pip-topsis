<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;

class SubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $kriterias = Kriteria::pluck('id', 'kode');

        $data = [
            'C1' => [
                ['nama_sub_kriteria' => 'Tidak Bekerja', 'nilai' => 5],
                ['nama_sub_kriteria' => 'Buruh Lepas / Petani', 'nilai' => 4],
                ['nama_sub_kriteria' => 'Wiraswasta', 'nilai' => 3],
                ['nama_sub_kriteria' => 'Karyawan Swasta', 'nilai' => 2],
                ['nama_sub_kriteria' => 'PNS / TNI / POLRI', 'nilai' => 1],
            ],
            'C2' => [
                ['nama_sub_kriteria' => '≤ Rp 500.000', 'nilai' => 5],
                ['nama_sub_kriteria' => 'Rp 500.001 - Rp 1.000.000', 'nilai' => 4],
                ['nama_sub_kriteria' => 'Rp 1.000.001 - Rp 2.000.000', 'nilai' => 3],
                ['nama_sub_kriteria' => 'Rp 2.000.001 - Rp 5.000.000', 'nilai' => 2],
                ['nama_sub_kriteria' => '≥ Rp 5.000.001', 'nilai' => 1],
            ],
            'C3' => [
                ['nama_sub_kriteria' => 'Tidak Bekerja', 'nilai' => 5],
                ['nama_sub_kriteria' => 'Buruh Lepas / Petani', 'nilai' => 4],
                ['nama_sub_kriteria' => 'Wiraswasta', 'nilai' => 3],
                ['nama_sub_kriteria' => 'Karyawan Swasta', 'nilai' => 2],
                ['nama_sub_kriteria' => 'PNS / TNI / POLRI', 'nilai' => 1],
            ],
            'C4' => [
                ['nama_sub_kriteria' => '≤ Rp 500.000', 'nilai' => 5],
                ['nama_sub_kriteria' => 'Rp 500.001 - Rp 1.000.000', 'nilai' => 4],
                ['nama_sub_kriteria' => 'Rp 1.000.001 - Rp 2.000.000', 'nilai' => 3],
                ['nama_sub_kriteria' => 'Rp 2.000.001 - Rp 5.000.000', 'nilai' => 2],
                ['nama_sub_kriteria' => '≥ Rp 5.000.001', 'nilai' => 1],
            ],
            'C5' => [
                ['nama_sub_kriteria' => '≥ 5 Orang', 'nilai' => 5],
                ['nama_sub_kriteria' => '4 Orang', 'nilai' => 4],
                ['nama_sub_kriteria' => '3 Orang', 'nilai' => 3],
                ['nama_sub_kriteria' => '2 Orang', 'nilai' => 2],
                ['nama_sub_kriteria' => '1 Orang', 'nilai' => 1],
            ],
            'C6' => [
                ['nama_sub_kriteria' => 'Yatim Piatu', 'nilai' => 5],
                ['nama_sub_kriteria' => 'Yatim', 'nilai' => 3],
                ['nama_sub_kriteria' => 'Piatu', 'nilai' => 2],
                ['nama_sub_kriteria' => 'Orang Tua Lengkap', 'nilai' => 1],
            ],
            'C7' => [
                ['nama_sub_kriteria' => 'Kost / Sewa', 'nilai' => 5],
                ['nama_sub_kriteria' => 'Asrama', 'nilai' => 4],
                ['nama_sub_kriteria' => 'Wali', 'nilai' => 3],
                ['nama_sub_kriteria' => 'Bersama Orang Tua', 'nilai' => 2],
            ],
            'C8' => [
                ['nama_sub_kriteria' => 'Jalan Kaki', 'nilai' => 5],
                ['nama_sub_kriteria' => 'Angkutan Umum', 'nilai' => 4],
                ['nama_sub_kriteria' => 'Sepeda Motor', 'nilai' => 3],
                ['nama_sub_kriteria' => 'Mobil Pribadi', 'nilai' => 2],
            ],
        ];

        foreach ($data as $kode => $subList) {
            if (! isset($kriterias[$kode])) {
                continue;
            }

            $kriteriaId = $kriterias[$kode];
            foreach ($subList as $sub) {
                SubKriteria::updateOrCreate(
                    [
                        'kriteria_id' => $kriteriaId,
                        'nama_sub_kriteria' => $sub['nama_sub_kriteria'],
                    ],
                    [
                        'nilai' => $sub['nilai'],
                    ]
                );
            }
        }
    }
}
