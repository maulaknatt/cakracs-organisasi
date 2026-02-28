<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSticker extends Model
{
    protected $fillable = ['user_id', 'sticker_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
