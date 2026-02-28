<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $fillable = [
        'nama_organisasi',
        'logo',
        'warna_tema',
        'role_user',
        'akses_anggota',
    ];
}
