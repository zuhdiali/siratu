<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'id_mitra',
        'no_rek',
        'kec_asal',
        'flag'
    ];

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_mitras', 'mitra_id', 'kegiatan_id')->withPivot('honor');
    }
}
