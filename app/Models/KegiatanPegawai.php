<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanPegawai extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_pegawais';

    protected $fillable = [
        'pegawai_id',
        'kegiatan_id',
        'translok',
        'bukti_pembayaran_id',
    ];
}
