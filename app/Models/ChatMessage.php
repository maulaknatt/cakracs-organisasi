<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['user_id', 'parent_id', 'poll_id', 'message', 'type', 'file_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(ChatMessage::class , 'parent_id')->with('user');
    }

    public function poll()
    {
        return $this->belongsTo(Poll::class)->with(['options', 'votes']);
    }
}
