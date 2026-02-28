<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'judul', 'deskripsi', 'penanggung_jawab', 'deadline', 'status', 'kegiatan_id',
    ];

    public function penanggungJawab()
    {
        return $this->belongsTo(User::class , 'penanggung_jawab');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
