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
        // Pindahkan akun yang sebelumnya ber-role tata_usaha menjadi kepala_sekolah
        DB::table('users')
            ->where('role', 'tata_usaha')
            ->update(['role' => 'kepala_sekolah']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['wali_kelas', 'kepala_sekolah'])->default('wali_kelas')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['wali_kelas', 'tata_usaha', 'kepala_sekolah'])->default('wali_kelas')->change();
        });
    }
};
