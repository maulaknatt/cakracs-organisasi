<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $fillable = ['title', 'date', 'is_active'];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function tokens()
    {
        return $this->hasMany(AttendanceToken::class);
    }
}
