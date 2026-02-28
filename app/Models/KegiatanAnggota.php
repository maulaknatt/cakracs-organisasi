<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanAnggota extends Model
{
    protected $table = 'kegiatan_anggota';

    protected $fillable = ['kegiatan_id', 'nama', 'jabatan'];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
