<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoSuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'foto_surat_masuk';

    protected $fillable = [
        'id_surat',
        'filename',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat');
    }
}
