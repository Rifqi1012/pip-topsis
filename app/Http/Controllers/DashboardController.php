<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilTopsis;
use App\Models\Pengaturan;
use App\Models\Siswa;
use App\Models\Kriteria;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $kuota = Pengaturan::where('key', 'kuota_penerima')->first()->value ?? 20;
        
        // Logika Filter Berdasarkan Hak Akses
        if ($user->role === 'wali_kelas') {
            // Wali Kelas: Hanya ambil data terkait siswa yang dikelolanya
            $jumlahSiswa = Siswa::where('user_id', $user->id)->count();
            $jumlahKriteria = Kriteria::count();
            
            // Jumlah penilaian (Siswa dari wali kelas ini yang sudah 'submitted' / lengkap)
            $jumlahPenilaian = Siswa::where('user_id', $user->id)
                                    ->where('status_data', 'submitted')
                                    ->count();
            
            // Jumlah penerima (Dari tabel hasil TOPSIS khusus siswa wali kelas ini)
            $hasilTopsisCount = HasilTopsis::whereHas('siswa', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
        } else {
            // Tata Usaha / Kepala Sekolah: Ambil keseluruhan data
            $jumlahSiswa = Siswa::count();
            $jumlahKriteria = Kriteria::count();
            $jumlahPenilaian = Siswa::where('status_data', 'submitted')->count();
            $hasilTopsisCount = HasilTopsis::count();
        }

        $jumlahPenerima = min($hasilTopsisCount, $kuota);

        return view('dashboard', compact(
            'jumlahSiswa',
            'jumlahKriteria',
            'jumlahPenilaian',
            'jumlahPenerima',
            'user'
        ));
    }
}
