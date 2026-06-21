<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'kode_siswa', 'nisn', 'nama_siswa', 'kelas', 'alamat', 'status_data',
        'c1_id', 'c2_id', 'c3_id', 'c4_id', 'c5_id', 'c6_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function c1() { return $this->belongsTo(SubKriteria::class, 'c1_id'); }
    public function c2() { return $this->belongsTo(SubKriteria::class, 'c2_id'); }
    public function c3() { return $this->belongsTo(SubKriteria::class, 'c3_id'); }
    public function c4() { return $this->belongsTo(SubKriteria::class, 'c4_id'); }
    public function c5() { return $this->belongsTo(SubKriteria::class, 'c5_id'); }
    public function c6() { return $this->belongsTo(SubKriteria::class, 'c6_id'); }
}
