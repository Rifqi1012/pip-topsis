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
        
        $jumlahSiswa = Siswa::count();
        $jumlahKriteria = Kriteria::count();
        $jumlahPenilaian = Siswa::where('status_data', 'submitted')->count();
        $hasilTopsisCount = HasilTopsis::count();
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
