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
        'tim',
    ];

    public function mitra(): BelongsToMany
    {
        return $this->belongsToMany(Mitra::class, 'kegiatan_mitras', 'kegiatan_id', 'mitra_id')->withPivot('jumlah', 'honor', 'estimasi_honor');
    }

    public function pegawai(): BelongsToMany
    {
        return $this->belongsToMany(Pegawai::class, 'kegiatan_pegawais', 'kegiatan_id', 'pegawai_id')->withPivot('translok');
    }
}
