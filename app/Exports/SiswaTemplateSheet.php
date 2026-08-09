<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateSheet implements FromArray, WithTitle, WithHeadings
{
    public function headings(): array
    {
        return [
            'No',
            'NISN',
            'Nama Siswa',
            'Kelas',
            'Alamat',
            'Tahun Ajaran'
        ];
    }

    public function array(): array
    {
        return [
            [
                '1',
                '1234567890',
                'Contoh Nama Siswa',
                'Kelas 1',
                'Jl. Contoh Alamat',
                '2024/2025'
            ]
        ];
    }

    public function title(): string
    {
        return 'Template Data';
    }
}
