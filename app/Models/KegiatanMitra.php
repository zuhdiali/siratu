<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanMitra extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_mitras';

    protected $fillable = [
        'mitra_id',
        'kegiatan_id',
        'jumlah',
        'honor',
        'translok',
        'estimasi_honor',
        'bukti_pembayaran_id',
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}
