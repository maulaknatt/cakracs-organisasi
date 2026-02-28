<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoiceChannel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'is_active', 'sort_order'];
}
