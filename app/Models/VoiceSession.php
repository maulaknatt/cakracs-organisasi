<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceSession extends Model
{
    protected $fillable = ['user_id', 'voice_channel_id', 'status', 'last_seen_at'];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(VoiceChannel::class, 'voice_channel_id');
    }
}
