<?php

namespace App\Console\Commands;

use App\Models\Siswa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ImportDummyDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:dummy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import dummy data siswa untuk pengujian TOPSIS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->error('Reset data contoh hanya diizinkan pada environment local atau testing.');

            return self::FAILURE;
        }
        if (! $this->confirm('Seluruh data lokal akan dihapus dan diganti data contoh. Lanjutkan?')) {
            return self::SUCCESS;
        }

        $this->info('Memulai proses import data dummy...');

        $this->info('Menjalankan migrate:fresh --seed...');
        Artisan::call('migrate:fresh', ['--seed' => true]);

        $this->info('Selesai! Data siswa, kriteria, dan sub kriteria telah berhasil di-seed ulang.');
        $this->info('Jumlah Siswa saat ini: '.Siswa::count());
        $this->info('Silakan login sebagai Kepala Sekolah untuk mengelola data master.');
    }
}
