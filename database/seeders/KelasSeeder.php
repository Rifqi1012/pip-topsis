<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = ['1', '2A', '2B', '3A', '3B', '4', '5', '6'];

        foreach ($kelasList as $nama) {
            Kelas::firstOrCreate(['nama' => $nama]);
        }
    }
}
