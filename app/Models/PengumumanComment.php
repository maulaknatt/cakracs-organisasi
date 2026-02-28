<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengumumanComment extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'pengumuman_id', 'isi', 'parent_id', 'is_edited'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(PengumumanComment::class , 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(PengumumanComment::class , 'parent_id')->orderBy('created_at', 'asc');
    }

    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class);
    }
}
