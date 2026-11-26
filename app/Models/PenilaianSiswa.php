<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianSiswa extends Model
{
    protected $fillable = [
        'siswa_id',
        'kriteria_id',
        'sub_kriteria_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class);
    }
}
