<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'kode_siswa', 'nisn', 'nama_siswa', 'kelas', 'periode', 'alamat', 'status_data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penilaians()
    {
        return $this->hasMany(PenilaianSiswa::class);
    }
}
