<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('kode_siswa')->unique();
            $table->string('nama_siswa');
            $table->string('kelas');
            $table->enum('status_data', ['draft', 'submitted'])->default('draft');
            $table->foreignId('c1_id')->constrained('sub_kriterias');
            $table->foreignId('c2_id')->constrained('sub_kriterias');
            $table->foreignId('c3_id')->constrained('sub_kriterias');
            $table->foreignId('c4_id')->constrained('sub_kriterias');
            $table->foreignId('c5_id')->constrained('sub_kriterias');
            $table->foreignId('c6_id')->constrained('sub_kriterias');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
