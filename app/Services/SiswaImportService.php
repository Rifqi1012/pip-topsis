<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Siswa;
use App\Models\SubKriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class SiswaImportService
{
    protected $subKriteriasCache = [];

    public function __construct()
    {
        // Pre-load all sub_kriterias for mapping performance
        $kriterias = Kriteria::with('subKriterias')->get();
        foreach ($kriterias as $kriteria) {
            $this->subKriteriasCache[$kriteria->kode] = $kriteria->subKriterias;
        }
    }

    /**
     * Get sheet names from an uploaded file
     */
    public function getSheetNames($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        if (strtolower($extension) === 'csv') {
            return ['Sheet1']; // CSVs don't have multiple sheets, represent as single sheet
        }

        // For Excel, we can get sheet names using standard PhpSpreadsheet reader
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        return $spreadsheet->getSheetNames();
    }

    /**
     * Parse and validate a specific sheet from the uploaded file
     */
    public function previewData($filePath, $sheetIndex = 0)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $isCsv = strtolower($extension) === 'csv';

        // Load data. We use a simple array import to get headers and rows easily.
        $data = Excel::toArray(new \stdClass, $filePath);

        // $data is an array of sheets. Pick the chosen sheet.
        $sheetData = $isCsv ? $data[0] : ($data[$sheetIndex] ?? []);

        if (empty($sheetData) || count($sheetData) < 2) {
            throw new \Exception("Sheet kosong atau tidak memiliki data yang cukup.");
        }

        // Assuming first row is header
        $headers = array_map(function($h) {
            return strtolower(trim($h ?? ''));
        }, $sheetData[0]);

        $findCol = function($names) use ($headers) {
            foreach ($names as $name) {
                $idx = array_search($name, $headers);
                if ($idx !== false) return $idx;
            }
            return false;
        };

        // Find required column indices for basic student data
        $colMap = [
            'nama' => $findCol(['nama', 'nama siswa']),
            'nisn' => $findCol(['nisn']),
            'kelas' => $findCol(['kelas', 'kelas / rombel saat ini', 'rombel']),
            'alamat' => $findCol(['alamat']),
            'tahun_ajaran' => $findCol(['tahun ajaran', 'periode']),
        ];

        // Ensure all required columns are found
        $missing = [];
        if ($colMap['nama'] === false) $missing[] = 'nama';
        if ($colMap['nisn'] === false) $missing[] = 'nisn';
        if ($colMap['kelas'] === false) $missing[] = 'kelas';
        if ($colMap['tahun_ajaran'] === false) $missing[] = 'tahun ajaran';

        if (!empty($missing)) {
            throw new \Exception("Kolom berikut tidak ditemukan di baris pertama (header): " . implode(', ', $missing) . ". Pastikan Anda menggunakan format template yang benar.");
        }

        $previewRows = [];
        $totalValid = 0;
        $totalError = 0;
        $totalDuplicate = 0;
        
        $existingNisnList = Siswa::whereNotNull('nisn')->pluck('nisn')->toArray();

        // Process data rows
        for ($i = 1; $i < count($sheetData); $i++) {
            $row = $sheetData[$i];
            
            // Skip empty rows (checking nisn and nama)
            if (empty(trim($row[$colMap['nisn']] ?? '')) && empty(trim($row[$colMap['nama']] ?? ''))) {
                continue;
            }

            $rowData = [
                'row_number' => $i + 1,
                'nama_siswa' => trim($row[$colMap['nama']] ?? ''),
                'nisn' => trim($row[$colMap['nisn']] ?? ''),
                'kelas' => trim($row[$colMap['kelas']] ?? ''),
                'alamat' => $colMap['alamat'] !== false ? trim($row[$colMap['alamat']] ?? '') : '-',
                'tahun_ajaran' => trim($row[$colMap['tahun_ajaran']] ?? ''),
                'is_valid' => true,
                'errors' => [],
            ];

            // Validation Rules
            if (empty($rowData['nama_siswa'])) {
                $rowData['is_valid'] = false;
                $rowData['errors'][] = 'Nama wajib diisi.';
            }

            if (empty($rowData['kelas'])) {
                $rowData['is_valid'] = false;
                $rowData['errors'][] = 'Kelas wajib diisi.';
            }

            if (!empty($rowData['nisn']) && in_array($rowData['nisn'], $existingNisnList)) {
                $rowData['is_valid'] = false;
                $rowData['errors'][] = 'NISN sudah terdaftar (Duplikat).';
                $totalDuplicate++;
            }

            // Removed C1-C6 validation as requested

            if ($rowData['is_valid']) {
                $totalValid++;
            } else {
                $totalError++;
            }

            $previewRows[] = $rowData;
        }

        return [
            'rows' => $previewRows,
            'summary' => [
                'total' => count($previewRows),
                'valid' => $totalValid,
                'error' => $totalError,
                'duplicate' => $totalDuplicate,
                'can_import' => $totalError === 0 && count($previewRows) > 0 // Strict policy: no partial import
            ]
        ];
    }

    /**
     * Exact matching helper for Sub Kriteria
     */
    private function findSubKriteriaId($kodeKriteria, $value)
    {
        if (empty($value)) return null;

        $normalizedValue = Str::lower(trim($value));
        $subs = $this->subKriteriasCache[$kodeKriteria] ?? [];

        foreach ($subs as $sub) {
            $normalizedSub = Str::lower(trim($sub->nama_sub_kriteria));
            if ($normalizedValue === $normalizedSub) {
                return $sub->id;
            }
        }
        
        return null;
    }

    /**
     * Process actual import
     */
    public function processImport(array $validData, $userId)
    {
        DB::beginTransaction();
        try {
            // Removed default periode logic since we now read it from file or use a fallback
            $currentYear = date('Y');
            $defaultPeriode = $currentYear . '/' . ($currentYear + 1);

            // Get next ID for kode_siswa
            $latest = Siswa::withTrashed()->orderBy('id', 'desc')->lockForUpdate()->first();
            $nextId = $latest ? $latest->id + 1 : 1;

            foreach ($validData as $data) {
                $siswa = new Siswa();
                $siswa->user_id = $userId;
                $siswa->kode_siswa = 'SW-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                $siswa->nisn = !empty($data['nisn']) ? $data['nisn'] : null;
                $siswa->nama_siswa = $data['nama_siswa'];
                $siswa->kelas = $data['kelas'];
                $siswa->periode = !empty($data['tahun_ajaran']) ? $data['tahun_ajaran'] : $defaultPeriode;
                $siswa->alamat = !empty($data['alamat']) ? $data['alamat'] : '-';
                
                $siswa->c1_id = null;
                $siswa->c2_id = null;
                $siswa->c3_id = null;
                $siswa->c4_id = null;
                $siswa->c5_id = null;
                $siswa->c6_id = null;
                
                $siswa->status_data = 'submitted';
                $siswa->save();

                $nextId++;
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import Siswa Error: " . $e->getMessage());
            throw $e;
        }
    }
}
