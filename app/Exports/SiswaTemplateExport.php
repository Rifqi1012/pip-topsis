<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SiswaTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new SiswaTemplateSheet,
            new PetunjukTemplateSheet,
        ];
    }
}
