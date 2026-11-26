<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $fillable = ['kode', 'nama_kriteria', 'atribut', 'bobot', 'urutan'];

    public const LABEL_BOBOT = [
        5 => 'Sangat Penting',
        4 => 'Penting',
        3 => 'Cukup Penting',
        2 => 'Kurang Penting',
        1 => 'Tidak Penting',
    ];

    public function getLabelBobotAttribute(): string
    {
        return self::LABEL_BOBOT[(int) $this->bobot] ?? '-';
    }

    public function subKriterias()
    {
        return $this->hasMany(SubKriteria::class)->orderBy('nilai', 'desc');
    }

    public function penilaians()
    {
        return $this->hasMany(PenilaianSiswa::class);
    }
}
