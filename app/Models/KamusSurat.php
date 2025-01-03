<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamusSurat extends Model
{
    use HasFactory;

    protected $table = 'kamus_nomor_surat';

    protected $fillable = [
        'tim',
        'kode_surat',
        'kode_surat_gabungan',
        'klasifikasi'
    ];
}
