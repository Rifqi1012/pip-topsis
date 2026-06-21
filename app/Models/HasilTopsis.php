<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilTopsis extends Model
{
    protected $table = 'hasil_topsis';

    protected $fillable = [
        'siswa_id',
        'nilai_preferensi',
        'd_plus',
        'd_minus',
        'ranking',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class)->withTrashed();
    }
}
