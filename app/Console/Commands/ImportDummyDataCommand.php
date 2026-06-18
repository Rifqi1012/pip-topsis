<?php

namespace App\Console\Commands;

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
        $this->info('Memulai proses import data dummy...');
        
        $this->info('Menjalankan migrate:fresh --seed...');
        Artisan::call('migrate:fresh', ['--seed' => true]);
        
        $this->info('Selesai! Data siswa, kriteria, dan sub kriteria telah berhasil di-seed ulang.');
        $this->info('Jumlah Siswa saat ini: ' . \App\Models\Siswa::count());
        $this->info('Silakan login sebagai Tata Usaha dan jalankan Hitung TOPSIS untuk melihat hasilnya.');
    }
}
