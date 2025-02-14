<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SBKS extends Model
{
    use HasFactory;

    protected $table = 'sbks';

    protected $fillable = [
        'nama_kegiatan',
        'tugas',
        'satuan',
        'honor_per_satuan',
        'tim',
        'ada_di_simeulue',
        'pjk',
        'singkatan_resmi',
        'beban_anggaran'
    ];
}
