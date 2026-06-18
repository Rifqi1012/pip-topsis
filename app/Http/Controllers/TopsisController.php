<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilTopsis;
use App\Services\TopsisService;
use Exception;

class TopsisController extends Controller
{
    protected $topsisService;

    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    public function index()
    {
        if (!in_array(auth()->user()->role, ['tata_usaha', 'kepala_sekolah'])) {
            abort(403, 'Unauthorized action.');
        }

        $kuota = \App\Models\Pengaturan::where('key', 'kuota_penerima')->first()->value ?? 20;
        $hasil = HasilTopsis::with('siswa.user')->orderBy('ranking', 'asc')->get();
        
        // Menentukan status rekomendasi
        foreach ($hasil as $h) {
            $h->status_rekomendasi = $h->ranking <= $kuota ? 'Direkomendasikan' : 'Cadangan';
        }

        return view('topsis.index', compact('hasil', 'kuota'));
    }

    public function calculate()
    {
        if (auth()->user()->role !== 'tata_usaha') {
            abort(403, 'Hanya Tata Usaha yang dapat menjalankan perhitungan.');
        }

        try {
            $this->topsisService->calculate(true); // Hitung & Simpan ke DB
            return redirect()->route('topsis.index')->with('success', 'Perhitungan TOPSIS berhasil diselesaikan!');
        } catch (Exception $e) {
            return redirect()->route('topsis.index')->with('error', $e->getMessage());
        }
    }

    public function transparansi()
    {
        if (!in_array(auth()->user()->role, ['tata_usaha', 'kepala_sekolah'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Kalkulasi ulang TANPA menyimpan ke database hanya untuk view transparansi
            $data = $this->topsisService->calculate(false);
            return view('topsis.transparansi', $data);
        } catch (Exception $e) {
            return redirect()->route('topsis.index')->with('error', $e->getMessage());
        }
    }
}
