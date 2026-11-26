<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    protected $fillable = ['kriteria_id', 'nama_sub_kriteria', 'nilai'];

    public const LABEL_NILAI = [
        5 => 'Sangat Layak',
        4 => 'Layak',
        3 => 'Cukup Layak',
        2 => 'Kurang Layak',
        1 => 'Tidak Layak',
    ];

    public function getLabelNilaiAttribute(): string
    {
        return self::LABEL_NILAI[(int) $this->nilai] ?? '-';
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
