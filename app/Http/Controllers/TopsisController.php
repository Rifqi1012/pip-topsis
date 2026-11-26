<?php

namespace App\Http\Controllers;

use App\Models\HasilTopsis;
use App\Models\Kelas;
use App\Models\Pengaturan;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\TopsisService;
use Exception;
use Illuminate\Http\Request;

class TopsisController extends Controller
{
    protected $topsisService;

    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    public function index(Request $request)
    {
        if (auth()->user()->role !== 'kepala_sekolah') {
            abort(403, 'Unauthorized action. Hanya Kepala Sekolah yang dapat melakukan proses seleksi.');
        }

        $kuota = Pengaturan::where('key', 'kuota_penerima')->first()->value ?? 20;

        $kelasList = Kelas::orderBy('nama', 'asc')->pluck('nama');
        if ($kelasList->isEmpty()) {
            $kelasList = Siswa::select('kelas')->whereNotNull('kelas')->distinct()->pluck('kelas');
        }
        $selectedKelas = $request->input('kelas', $kelasList->first());

        $periodeList = TahunAjaran::orderBy('tahun_mulai', 'desc')->pluck('nama');
        if ($periodeList->isEmpty()) {
            $periodeList = Siswa::select('periode')->whereNotNull('periode')->distinct()->pluck('periode');
        }
        $selectedPeriode = $request->input('periode', $periodeList->first());

        $selectedStatus = $request->input('status', 'semua');

        $query = HasilTopsis::with('siswa.user')->orderBy('ranking', 'asc');

        if ($selectedKelas || $selectedPeriode) {
            $query->whereHas('siswa', function ($q) use ($selectedKelas, $selectedPeriode) {
                if ($selectedKelas) {
                    $q->where('kelas', $selectedKelas);
                }
                if ($selectedPeriode) {
                    $q->where('periode', $selectedPeriode);
                }
            });
        }

        $hasil = $query->get();

        // Menentukan status rekomendasi
        foreach ($hasil as $h) {
            $h->status_rekomendasi = $h->ranking <= $kuota ? 'Direkomendasikan' : 'Cadangan';
        }

        if ($selectedStatus === 'direkomendasikan') {
            $hasil = $hasil->filter(function ($item) {
                return $item->status_rekomendasi === 'Direkomendasikan';
            });
        } elseif ($selectedStatus === 'cadangan') {
            $hasil = $hasil->filter(function ($item) {
                return $item->status_rekomendasi === 'Cadangan';
            });
        }

        return view('topsis.index', compact('hasil', 'kuota', 'kelasList', 'selectedKelas', 'periodeList', 'selectedPeriode', 'selectedStatus'));
    }

    public function calculate(Request $request)
    {
        if (auth()->user()->role !== 'kepala_sekolah') {
            abort(403, 'Hanya Kepala Sekolah yang dapat menjalankan perhitungan.');
        }

        $request->validate([
            'kelas' => 'nullable|string|max:50',
            'periode' => 'nullable|string|max:100',
        ]);

        try {
            $kelas = $request->input('kelas');
            $periode = $request->input('periode');
            $this->topsisService->calculate(true, $kelas, $periode); // Hitung & Simpan ke DB

            return redirect()->route('topsis.index', ['kelas' => $kelas, 'periode' => $periode])->with('success', "Proses Seleksi berhasil diselesaikan untuk Kelas {$kelas} (Periode: {$periode})!");
        } catch (Exception $e) {
            return redirect()->route('topsis.index', ['kelas' => $request->input('kelas'), 'periode' => $request->input('periode')])->with('error', $e->getMessage());
        }
    }

    public function transparansi(Request $request)
    {
        if (auth()->user()->role !== 'kepala_sekolah') {
            abort(403, 'Unauthorized action. Hanya Kepala Sekolah yang dapat mengakses transparansi.');
        }

        $request->validate([
            'kelas' => 'nullable|string|max:50',
            'periode' => 'nullable|string|max:100',
        ]);

        try {
            $kelas = $request->input('kelas');
            $periode = $request->input('periode');
            // Kalkulasi ulang TANPA menyimpan ke database hanya untuk view transparansi khusus kelas dan periode terpilih
            $data = $this->topsisService->calculate(false, $kelas, $periode);
            $data['kelas'] = $kelas;
            $data['periode'] = $periode;

            return view('topsis.transparansi', $data);
        } catch (Exception $e) {
            return redirect()->route('topsis.index')->with('error', $e->getMessage());
        }
    }
}
