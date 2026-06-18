<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HasilTopsisExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $hasil;

    public function __construct($hasil)
    {
        $this->hasil = $hasil;
    }

    public function collection()
    {
        return collect($this->hasil);
    }

    public function headings(): array
    {
        return [
            'Ranking',
            'Kode Siswa',
            'Nama Siswa',
            'Kelas',
            'Nilai Preferensi',
            'Status Rekomendasi'
        ];
    }

    public function map($row): array
    {
        return [
            $row->ranking,
            $row->siswa->kode_siswa,
            $row->siswa->nama_siswa,
            $row->siswa->kelas,
            number_format($row->nilai_preferensi, 4),
            $row->status_rekomendasi
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
