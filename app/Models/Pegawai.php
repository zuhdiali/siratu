<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pegawai extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'no_rek',
        'kec_asal',
        'flag',
        'username',
        'password',
        'role',
        'tim',
    ];

    public function kegiatan(): BelongsToMany
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_pegawais', 'pegawai_id', 'kegiatan_id')->withPivot('translok');
    }
}
