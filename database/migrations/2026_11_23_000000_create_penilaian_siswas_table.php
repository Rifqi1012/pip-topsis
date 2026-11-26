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
        Schema::create('penilaian_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('kriteria_id')->constrained('kriterias')->cascadeOnDelete();
            $table->foreignId('sub_kriteria_id')->constrained('sub_kriterias')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['siswa_id', 'kriteria_id']);
        });

        // Migrasi data penilaian lama dari kolom c1_id..c6_id di tabel siswas (jika ada)
        if (Schema::hasColumns('siswas', ['c1_id', 'c2_id', 'c3_id', 'c4_id', 'c5_id', 'c6_id'])) {
            $kriteriaMap = DB::table('kriterias')
                ->whereIn('kode', ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'])
                ->pluck('id', 'kode');

            $siswas = DB::table('siswas')->select('id', 'c1_id', 'c2_id', 'c3_id', 'c4_id', 'c5_id', 'c6_id')->get();
            $now = now();
            $rows = [];

            foreach ($siswas as $siswa) {
                for ($i = 1; $i <= 6; $i++) {
                    $col = "c{$i}_id";
                    $kode = "C{$i}";
                    if (! empty($siswa->$col) && isset($kriteriaMap[$kode])) {
                        $rows[] = [
                            'siswa_id' => $siswa->id,
                            'kriteria_id' => $kriteriaMap[$kode],
                            'sub_kriteria_id' => $siswa->$col,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (! empty($rows)) {
                DB::table('penilaian_siswas')->insertOrIgnore($rows);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_siswas');
    }
};
