<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;
    protected $table = 'arsips';

    protected $fillable = [
        'judul',
        'kegiatan_id',
        'deskripsi',
    ];

    public function attachments()
    {
        return $this->hasMany(ArsipAttachment::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
