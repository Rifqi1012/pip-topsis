<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengaturan::updateOrCreate(
            ['key' => 'kuota_penerima'],
            ['value' => '20']
        );
    }
}
