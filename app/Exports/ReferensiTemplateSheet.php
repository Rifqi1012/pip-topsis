<?php

namespace App\Exports;

use App\Models\Kriteria;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReferensiTemplateSheet implements FromArray, WithTitle
{
    public function array(): array
    {
        $kriterias = Kriteria::with('subKriterias')->get();
        $data = [
            ['DATA REFERENSI (PASTIKAN PENULISAN SAMA PERSIS)'],
            ['']
        ];

        foreach ($kriterias as $kriteria) {
            $data[] = ["=== {$kriteria->nama_kriteria} ({$kriteria->kode}) ==="];
            foreach ($kriteria->subKriterias as $sub) {
                $data[] = [$sub->nama_sub_kriteria];
            }
            $data[] = [''];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Data Referensi';
    }
}
