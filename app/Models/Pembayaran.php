<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'id_kegiatan',
        'nominal',
        'bukti_pembayaran',
    ];

    // public function kegiatanMitra()
    // {
    //     return $this->belongsTo(KegiatanMitra::class, 'bukti_pembayaran_id');
    // }
}
