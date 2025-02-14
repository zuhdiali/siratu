<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tgl_mulai',
        'tgl_selesai',
        'satuan_honor_pengawasan',
        'honor_pengawasan',
        'satuan_honor_pencacahan',
        'honor_pencacahan',
        'flag_pembayaran',
        'id_pjk',
        'progress',
        'tim',
        'beban_anggaran'
    ];

    public function mitra(): BelongsToMany
    {
        return $this->belongsToMany(Mitra::class, 'kegiatan_mitras')->withPivot('jumlah', 'is_pml', 'honor', 'estimasi_honor');
    }

    public function pegawai(): BelongsToMany
    {
        return $this->belongsToMany(Pegawai::class, 'kegiatan_pegawais')->withPivot('translok');
    }

    public function kegiatanMitra()
    {
        return $this->hasMany(KegiatanMitra::class, 'kegiatan_id');
    }
}
