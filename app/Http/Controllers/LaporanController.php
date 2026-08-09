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

        $data = $this->getFilteredData($request);
        
        $pdf = Pdf::loadView('laporan.pdf', [
            'hasil' => $data['hasil'],
            'filter' => $data['status'] ?? 'semua',
            'status' => $data['status'],
            'kelas' => $data['kelas'],
            'periode' => $data['periode'],
        ]);

        $kelasStr = !empty($data['kelas']) ? '_Kelas_' . str_replace([' ', '/', '\\'], '_', $data['kelas']) : '';
        $periodeStr = !empty($data['periode']) ? '_Periode_' . str_replace([' ', '/', '\\'], '_', $data['periode']) : '';
        $filename = 'Laporan_Penerima_PIP' . $kelasStr . $periodeStr . '_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        if (!in_array(auth()->user()->role, ['tata_usaha', 'kepala_sekolah'])) {
            abort(403, 'Unauthorized action.');
        }

        $data = $this->getFilteredData($request);
        
        $kelasStr = !empty($data['kelas']) ? '_Kelas_' . str_replace([' ', '/', '\\'], '_', $data['kelas']) : '';
        $periodeStr = !empty($data['periode']) ? '_Periode_' . str_replace([' ', '/', '\\'], '_', $data['periode']) : '';
        $filename = 'Laporan_Penerima_PIP' . $kelasStr . $periodeStr . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new HasilTopsisExport($data['hasil']), $filename);
    }

    private function getFilteredData(Request $request)
    {
        $kuota = Pengaturan::where('key', 'kuota_penerima')->first()->value ?? 20;
        
        $query = HasilTopsis::with('siswa')->orderBy('ranking', 'asc');

        if ($request->filled('kelas') || $request->filled('periode')) {
            $query->whereHas('siswa', function($q) use ($request) {
                if ($request->filled('kelas')) {
                    $q->where('kelas', $request->input('kelas'));
                }
                if ($request->filled('periode')) {
                    $q->where('periode', $request->input('periode'));
                }
            });
        }

        $hasil = $query->get();

        // Menentukan status rekomendasi
        foreach ($hasil as $h) {
            $h->status_rekomendasi = $h->ranking <= $kuota ? 'Direkomendasikan' : 'Cadangan';
        }

        $status = $request->input('status', $request->input('filter', 'semua'));

        // Apply filter
        if ($status === 'direkomendasikan') {
            $hasil = $hasil->where('status_rekomendasi', 'Direkomendasikan')->values();
        } elseif ($status === 'cadangan') {
            $hasil = $hasil->where('status_rekomendasi', 'Cadangan')->values();
        }

        return [
            'hasil' => $hasil,
            'kelas' => $request->input('kelas'),
            'periode' => $request->input('periode'),
            'status' => $status,
        ];
    }
}
