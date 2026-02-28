<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ChatMessage;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'foto_profil',
        'role_id',
        'jabatan',
        'is_active',
        'last_chat_read_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Kegiatan dimana user menjadi panitia.
     */
    public function panitiaKegiatan()
    {
        return $this->belongsToMany(Kegiatan::class , 'kegiatan_panitia', 'user_id', 'kegiatan_id')
            ->withPivot('jabatan')
            ->withTimestamps();
    }

    /** Kegiatan dimana user menjadi panitia */
    public function kegiatansAsPanitia()
    {
        return $this->belongsToMany(Kegiatan::class , 'kegiatan_panitia', 'user_id', 'kegiatan_id')
            ->withPivot('jabatan')
            ->withTimestamps();
    }

    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    public function isOnline()
    {
        return \DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes(5)->getTimestamp())
            ->exists();
    }

    public function getRoleNameAttribute()
    {
        return $this->role->nama_role ?? '-';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $roleName): bool
    {
        if (!$this->role) {
            return false;
        }

        return strtolower($this->role->nama_role) === strtolower($roleName);
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * Check if user is Admin (Ketua/Wakil)
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if user is Pengurus
     */
    public function isPengurus(): bool
    {
        return $this->hasRole('Pengurus');
    }

    /**
     * Check if user is Anggota
     */
    public function isAnggota(): bool
    {
        return $this->hasRole('Anggota');
    }

    /**
     * Check if user can access module
     */
    public function canAccess(string $module): bool
    {
        // Jika tidak ada role, tidak bisa akses
        if (!$this->role) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true; // Super Admin has access to everything
        }

        $permissions = $this->role->permissions ?? [];

        return in_array($module, $permissions);
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Jika tidak ada role, tidak punya permission
        if (!$this->role) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        $permissions = $this->role->permissions ?? [];

        return in_array($permission, $permissions);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_chat_read_at' => 'datetime',
        ];
    }

    public function getUnreadChatCountAttribute()
    {
        $query = ChatMessage::where('user_id', '!=', $this->id);

        if ($this->last_chat_read_at) {
            $query->where('created_at', '>', $this->last_chat_read_at);
        }
        return $query->count();
    }

    public function getHasUnreadMentionsAttribute()
    {
        $lastRead = $this->last_chat_read_at;
        $query = ChatMessage::where('user_id', '!=', $this->id);

        if ($lastRead) {
            $query->where('created_at', '>', $lastRead);
        }

        return $query->where(function ($q) {
            $q->where('message', 'like', '%@everyone%')
                ->orWhere('message', 'like', "%@{$this->name}%");
        })->exists();
    }

    public function stickers()
    {
        return $this->hasMany(UserSticker::class);
    }

    public function pengumumanLikes()
    {
        return $this->hasMany(PengumumanLike::class);
    }

    public function pengumumanComments()
    {
        return $this->hasMany(PengumumanComment::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
