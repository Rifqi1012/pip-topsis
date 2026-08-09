<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SiswaImportService;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SubKriteria;
use App\Models\Kriteria;

class SiswaImportController extends Controller
{
    protected $importService;

    public function __construct(SiswaImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        // Only wali_kelas can access
        if (auth()->user()->role !== 'wali_kelas') {
            abort(403, 'Unauthorized action.');
        }

        return view('siswa-import.index');
    }

    public function getSheets(Request $request)
    {
        if (auth()->user()->role !== 'wali_kelas') {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('file');
            
            // Generate unique filename for temporary storage during preview process
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('temp_imports', $fileName);
            $fullPath = Storage::path($path);

            $sheets = $this->importService->getSheetNames($fullPath);

            return response()->json([
                'success' => true,
                'path' => $path,
                'sheets' => $sheets,
                'isCsv' => strtolower($file->getClientOriginalExtension()) === 'csv'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function preview(Request $request)
    {
        if (auth()->user()->role !== 'wali_kelas') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'temp_path' => 'required|string',
            'sheet_index' => 'required|integer'
        ]);

        $fullPath = Storage::path($request->temp_path);
        
        if (!file_exists($fullPath)) {
            return redirect()->route('siswa-import.index')->with('error', 'File tidak ditemukan. Silakan upload ulang.');
        }

        try {
            $previewResult = $this->importService->previewData($fullPath, $request->sheet_index);
            
            // Keep the temp file path in session so we can process it later
            session(['import_data' => $previewResult['rows']]);
            session(['temp_file_path' => $request->temp_path]);

            return view('siswa-import.preview', [
                'rows' => $previewResult['rows'],
                'summary' => $previewResult['summary']
            ]);
        } catch (\Exception $e) {
            return redirect()->route('siswa-import.index')->with('error', $e->getMessage());
        }
    }

    public function process(Request $request)
    {
        if (auth()->user()->role !== 'wali_kelas') {
            abort(403, 'Unauthorized action.');
        }

        $validData = session('import_data', []);
        $tempFilePath = session('temp_file_path');

        if (empty($validData)) {
            return redirect()->route('siswa-import.index')->with('error', 'Tidak ada data valid untuk di-import atau session telah kedaluwarsa.');
        }

        // Check if there are any errors. If there are, block import.
        $hasError = false;
        foreach ($validData as $row) {
            if (!$row['is_valid']) {
                $hasError = true;
                break;
            }
        }

        if ($hasError) {
            return redirect()->route('siswa-import.index')->with('error', 'Terdapat data invalid pada file yang Anda unggah. Import dibatalkan karena ada baris yang error.');
        }

        try {
            $this->importService->processImport($validData, auth()->id());
            
            // Clean up
            if ($tempFilePath && Storage::exists($tempFilePath)) {
                Storage::delete($tempFilePath);
            }
            session()->forget(['import_data', 'temp_file_path']);

            return redirect()->route('siswas.index')->with('success', count($validData) . ' Data siswa berhasil di-import.');
        } catch (\Exception $e) {
            return redirect()->route('siswa-import.index')->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate($type)
    {
        if (!in_array($type, ['xlsx', 'csv'])) {
            abort(404);
        }

        // Instead of generating Excel using class, we can return a static file if it exists, 
        // or generate it on the fly using PhpSpreadsheet since Maatwebsite wraps it.
        // Creating a simple export class on the fly.
        $export = new \App\Exports\SiswaTemplateExport();
        $fileName = 'Template_Import_Siswa.' . $type;
        
        return Excel::download($export, $fileName);
    }
}
