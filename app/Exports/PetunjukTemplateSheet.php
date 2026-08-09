<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class PetunjukTemplateSheet implements FromArray, WithTitle
{
    public function array(): array
    {
        return [
            ['PETUNJUK PENGISIAN DATA IMPORT SISWA'],
            [''],
            ['1. Kolom NISN, Nama Siswa, Kelas, dan Tahun Ajaran WAJIB diisi.'],
            ['2. Kolom NISN wajib unik (tidak boleh sama dengan siswa yang sudah ada di sistem).'],
            ['3. Kolom Tahun Ajaran harus disesuaikan dengan format tahun berjalan (misal: 2024/2025).'],
            ['4. Baris pertama (header) tidak boleh dihapus atau diubah namanya.'],
        ];
    }

    public function title(): string
    {
        return 'Petunjuk';
    }
}
