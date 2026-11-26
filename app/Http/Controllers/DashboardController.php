<?php

namespace App\Http\Controllers;

use App\Models\HasilTopsis;
use App\Models\Kriteria;
use App\Models\Pengaturan;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $kuota = Pengaturan::where('key', 'kuota_penerima')->first()->value ?? 20;

        $siswaQuery = Siswa::query();
        $hasilQuery = HasilTopsis::where('ranking', '<=', $kuota);

        if ($user->role === 'wali_kelas') {
            $siswaQuery->where('user_id', $user->id);
            $hasilQuery->whereHas('siswa', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $jumlahSiswa = (clone $siswaQuery)->count();
        $jumlahKriteria = Kriteria::count();
        $jumlahPenilaian = $jumlahKriteria > 0
            ? $siswaQuery->where('status_data', 'submitted')->has('penilaians', '=', $jumlahKriteria)->count()
            : 0;
        $jumlahPenerima = $hasilQuery->count();

        return view('dashboard', compact(
            'jumlahSiswa',
            'jumlahKriteria',
            'jumlahPenilaian',
            'jumlahPenerima',
            'user'
        ));
    }
}
