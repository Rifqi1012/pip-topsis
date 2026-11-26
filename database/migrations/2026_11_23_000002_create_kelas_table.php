<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->timestamps();
        });

        $defaultKelas = ['1', '2A', '2B', '3A', '3B', '4', '5', '6'];

        // Sertakan juga nama kelas yang sudah ada di tabel siswas agar data lama tetap cocok
        $existingKelas = DB::table('siswas')
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->pluck('kelas')
            ->map(fn ($k) => trim($k))
            ->toArray();

        $allKelas = array_values(array_unique(array_merge($defaultKelas, $existingKelas)));
        $now = now();
        $rows = array_map(fn ($nama) => [
            'nama' => $nama,
            'created_at' => $now,
            'updated_at' => $now,
        ], $allKelas);

        if (! empty($rows)) {
            DB::table('kelas')->insertOrIgnore($rows);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
