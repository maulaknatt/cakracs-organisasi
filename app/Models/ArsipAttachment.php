<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipAttachment extends Model
{
    protected $fillable = [
        'arsip_id',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
    ];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }
}
