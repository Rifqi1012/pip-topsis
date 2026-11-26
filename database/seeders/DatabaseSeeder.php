<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Wali Kelas',
            'email' => 'wali@sekolah.com',
            'password' => bcrypt('password'),
            'role' => 'wali_kelas',
        ]);

        User::factory()->create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@sekolah.com',
            'password' => bcrypt('password'),
            'role' => 'kepala_sekolah',
        ]);

        $this->call([
            KelasSeeder::class,
            KriteriaSeeder::class,
            SubKriteriaSeeder::class,
            PengaturanSeeder::class,
        ]);
    }
}
