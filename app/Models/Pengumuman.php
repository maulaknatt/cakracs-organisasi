<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;
    protected $table = 'pengumumen'; // Matches migration table name

    protected $fillable = [
        'judul',
        'isi',
        'is_pinned',
        'tanggal',
        'kegiatan_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function likes()
    {
        return $this->hasMany(PengumumanLike::class);
    }

    public function comments()
    {
        return $this->hasMany(PengumumanComment::class);
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
