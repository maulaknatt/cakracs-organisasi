<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul', 'tanggal', 'jenis', 'deskripsi', 'jumlah', 'kegiatan_id',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(\App\Models\Kegiatan::class);
    }
}
