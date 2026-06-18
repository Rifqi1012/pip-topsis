<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $c1 = Kriteria::where('kode', 'C1')->first()->id;
        $c2 = Kriteria::where('kode', 'C2')->first()->id;
        $c3 = Kriteria::where('kode', 'C3')->first()->id;
        $c4 = Kriteria::where('kode', 'C4')->first()->id;
        $c5 = Kriteria::where('kode', 'C5')->first()->id;
        $c6 = Kriteria::where('kode', 'C6')->first()->id;

        $subKriterias = [
            // C1
            ['kriteria_id' => $c1, 'nama_sub_kriteria' => 'Tidak Bekerja', 'nilai' => 5],
            ['kriteria_id' => $c1, 'nama_sub_kriteria' => 'Buruh Lepas / Petani', 'nilai' => 4],
            ['kriteria_id' => $c1, 'nama_sub_kriteria' => 'Wiraswasta', 'nilai' => 3],
            ['kriteria_id' => $c1, 'nama_sub_kriteria' => 'Karyawan Swasta', 'nilai' => 2],
            ['kriteria_id' => $c1, 'nama_sub_kriteria' => 'PNS / TNI / POLRI', 'nilai' => 1],
            
            // C2
            ['kriteria_id' => $c2, 'nama_sub_kriteria' => '≤ Rp 500.000', 'nilai' => 5],
            ['kriteria_id' => $c2, 'nama_sub_kriteria' => 'Rp 500.001 - Rp 1.000.000', 'nilai' => 4],
            ['kriteria_id' => $c2, 'nama_sub_kriteria' => 'Rp 1.000.001 - Rp 2.000.000', 'nilai' => 3],
            ['kriteria_id' => $c2, 'nama_sub_kriteria' => 'Rp 2.000.001 - Rp 5.000.000', 'nilai' => 2],
            ['kriteria_id' => $c2, 'nama_sub_kriteria' => '≥ Rp 5.000.001', 'nilai' => 1],
            
            // C3
            ['kriteria_id' => $c3, 'nama_sub_kriteria' => 'Tidak Bekerja', 'nilai' => 5],
            ['kriteria_id' => $c3, 'nama_sub_kriteria' => 'Buruh Lepas / Petani', 'nilai' => 4],
            ['kriteria_id' => $c3, 'nama_sub_kriteria' => 'Wiraswasta', 'nilai' => 3],
            ['kriteria_id' => $c3, 'nama_sub_kriteria' => 'Karyawan Swasta', 'nilai' => 2],
            ['kriteria_id' => $c3, 'nama_sub_kriteria' => 'PNS / TNI / POLRI', 'nilai' => 1],
            
            // C4
            ['kriteria_id' => $c4, 'nama_sub_kriteria' => '≤ Rp 500.000', 'nilai' => 5],
            ['kriteria_id' => $c4, 'nama_sub_kriteria' => 'Rp 500.001 - Rp 1.000.000', 'nilai' => 4],
            ['kriteria_id' => $c4, 'nama_sub_kriteria' => 'Rp 1.000.001 - Rp 2.000.000', 'nilai' => 3],
            ['kriteria_id' => $c4, 'nama_sub_kriteria' => 'Rp 2.000.001 - Rp 5.000.000', 'nilai' => 2],
            ['kriteria_id' => $c4, 'nama_sub_kriteria' => '≥ Rp 5.000.001', 'nilai' => 1],

            // C5
            ['kriteria_id' => $c5, 'nama_sub_kriteria' => '≥ 5 Orang', 'nilai' => 5],
            ['kriteria_id' => $c5, 'nama_sub_kriteria' => '4 Orang', 'nilai' => 4],
            ['kriteria_id' => $c5, 'nama_sub_kriteria' => '3 Orang', 'nilai' => 3],
            ['kriteria_id' => $c5, 'nama_sub_kriteria' => '2 Orang', 'nilai' => 2],
            ['kriteria_id' => $c5, 'nama_sub_kriteria' => '1 Orang', 'nilai' => 1],

            // C6
            ['kriteria_id' => $c6, 'nama_sub_kriteria' => 'Yatim Piatu', 'nilai' => 5],
            ['kriteria_id' => $c6, 'nama_sub_kriteria' => 'Yatim', 'nilai' => 4],
            ['kriteria_id' => $c6, 'nama_sub_kriteria' => 'Piatu', 'nilai' => 3],
            ['kriteria_id' => $c6, 'nama_sub_kriteria' => 'Orang Tua Lengkap Tetapi Tidak Mampu', 'nilai' => 2],
            ['kriteria_id' => $c6, 'nama_sub_kriteria' => 'Orang Tua Lengkap dan Mampu', 'nilai' => 1],
        ];

        foreach ($subKriterias as $sk) {
            SubKriteria::create($sk);
        }
    }
}
