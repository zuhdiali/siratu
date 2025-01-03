<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surats';

    protected $fillable = [
        'nomor_surat',
        'jenis_surat',
        'no_terakhir',
        'tgl_awal_kegiatan',
        'tgl_akhir_kegiatan',
        'perihal',
        'no_surat_masuk',
        'dinas_surat_masuk',
        'id_pembuat_surat',
        'id_kegiatan',
        'file',
    ];
}
