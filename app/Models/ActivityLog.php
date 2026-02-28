<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'role',
        'action',
        'module',
        'target_id',
        'description',
        'old_value',
        'new_value',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action badge color
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'create' => 'emerald',
            'update' => 'blue',
            'delete' => 'red',
            'login' => 'green',
            'logout' => 'gray',
            'upload' => 'purple',
            default => 'slate',
        };
    }

    /**
     * Get action label in Indonesian
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create' => 'Tambah',
            'update' => 'Edit',
            'delete' => 'Hapus',
            'login' => 'Login',
            'logout' => 'Logout',
            'upload' => 'Upload',
            default => ucfirst($this->action),
        };
    }
}
