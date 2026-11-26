<?php

namespace App\Services;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SiswaImportService
{
    private function rules(): array
    {
        return [
            'nama_siswa' => 'required|string|max:255',
            'nisn' => ['required', 'string', 'max:20', Rule::unique('siswas', 'nisn')->withoutTrashed()],
            'kelas' => 'required|string|max:50',
            'tahun_ajaran' => 'required|string|max:100',
            'alamat' => 'nullable|string',
        ];
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
        $reader = IOFactory::createReaderForFile($filePath);

        return $reader->listWorksheetNames($filePath);
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
            throw new \Exception('Sheet kosong atau tidak memiliki data yang cukup.');
        }

        // Assuming first row is header
        $headers = array_map(function ($h) {
            return strtolower(trim($h ?? ''));
        }, $sheetData[0]);

        $findCol = function ($names) use ($headers) {
            foreach ($names as $name) {
                $idx = array_search($name, $headers);
                if ($idx !== false) {
                    return $idx;
                }
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
        if ($colMap['nama'] === false) {
            $missing[] = 'nama';
        }
        if ($colMap['nisn'] === false) {
            $missing[] = 'nisn';
        }
        if ($colMap['kelas'] === false) {
            $missing[] = 'kelas';
        }
        if ($colMap['tahun_ajaran'] === false) {
            $missing[] = 'tahun ajaran';
        }

        if (! empty($missing)) {
            throw new \Exception('Kolom berikut tidak ditemukan di baris pertama (header): '.implode(', ', $missing).'. Pastikan Anda menggunakan format template yang benar.');
        }

        $previewRows = [];
        $totalValid = 0;
        $totalError = 0;
        $totalDuplicate = 0;

        $seenNisn = [];

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

            $validator = Validator::make($rowData, $this->rules());
            $rowData['errors'] = $validator->errors()->all();
            if (isset($seenNisn[$rowData['nisn']])) {
                $rowData['errors'][] = 'NISN muncul lebih dari sekali dalam file.';
            }
            if (isset($seenNisn[$rowData['nisn']]) || isset($validator->failed()['nisn']['Unique'])) {
                $totalDuplicate++;
            }
            $seenNisn[$rowData['nisn']] = true;
            $rowData['is_valid'] = empty($rowData['errors']);

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
                'can_import' => $totalError === 0 && count($previewRows) > 0, // Strict policy: no partial import
            ],
        ];
    }

    /**
     * Process actual import
     */
    public function processImport(array $validData, $userId)
    {
        return DB::transaction(function () use ($validData, $userId) {
            // Get next ID for kode_siswa
            $latest = Siswa::withTrashed()->orderBy('id', 'desc')->lockForUpdate()->first();
            $nextId = $latest ? $latest->id + 1 : 1;

            foreach ($validData as $data) {
                Validator::make($data, $this->rules())->validate();
                $siswa = new Siswa;
                $siswa->user_id = $userId;
                $siswa->kode_siswa = 'SW-'.str_pad($nextId, 4, '0', STR_PAD_LEFT);
                $siswa->nisn = $data['nisn'];
                $siswa->nama_siswa = $data['nama_siswa'];
                $siswa->kelas = $data['kelas'];
                $siswa->periode = $data['tahun_ajaran'];
                $siswa->alamat = ! empty($data['alamat']) ? $data['alamat'] : '-';

                $siswa->status_data = 'submitted';
                $siswa->save();

                $nextId++;
            }

            return true;
        });
    }
}
