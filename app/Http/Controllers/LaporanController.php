<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilTopsis;
use App\Models\Pengaturan;
use App\Exports\HasilTopsisExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function exportPdf(Request $request)
    {
        if (!in_array(auth()->user()->role, ['tata_usaha', 'kepala_sekolah'])) {
            abort(403, 'Unauthorized action.');
        }

        $data = $this->getFilteredData($request->filter);
        
        $pdf = Pdf::loadView('laporan.pdf', [
            'hasil' => $data['hasil'],
            'filter' => $request->filter ?? 'semua'
        ]);

        return $pdf->download('Laporan_Penerima_PIP_' . date('Ymd_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        if (!in_array(auth()->user()->role, ['tata_usaha', 'kepala_sekolah'])) {
            abort(403, 'Unauthorized action.');
        }

        $data = $this->getFilteredData($request->filter);
        
        return Excel::download(new HasilTopsisExport($data['hasil']), 'Laporan_Penerima_PIP_' . date('Ymd_His') . '.xlsx');
    }

    private function getFilteredData($filter)
    {
        $kuota = Pengaturan::where('key', 'kuota_penerima')->first()->value ?? 20;
        $hasil = HasilTopsis::with('siswa')->orderBy('ranking', 'asc')->get();

        // Menentukan status rekomendasi
        foreach ($hasil as $h) {
            $h->status_rekomendasi = $h->ranking <= $kuota ? 'Direkomendasikan' : 'Cadangan';
        }

        // Apply filter
        if ($filter === 'direkomendasikan') {
            $hasil = $hasil->where('status_rekomendasi', 'Direkomendasikan')->values();
        } elseif ($filter === 'cadangan') {
            $hasil = $hasil->where('status_rekomendasi', 'Cadangan')->values();
        }

        return ['hasil' => $hasil];
    }
}
