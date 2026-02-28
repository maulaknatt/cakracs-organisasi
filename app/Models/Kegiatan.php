<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai',
    ];

    protected $appends = ['progress', 'status'];

    public function getStatusAttribute()
    {
        $now = now();
        if ($now < $this->tanggal_mulai) {
            return 'akan_datang';
        }
        elseif ($now > $this->tanggal_selesai) {
            return 'selesai';
        }
        return 'aktif';
    }

    public function getProgressAttribute()
    {
        // Jika sudah dihitung lewat withCount di query, gunakan itu agar efisien
        if (isset($this->attributes['tugas_count'])) {
            $total = $this->attributes['tugas_count'];
            $completed = $this->attributes['selesai_tugas_count'] ?? 0;
        }
        else {
            // Fallback: Lazy load if not eager loaded
            $total = $this->tugas()->count();
            $completed = $total > 0 ? $this->tugas()->where('status', 'done')->count() : 0;
        }

        if ($total > 0) {
            return round(($completed / $total) * 100);
        }

        // Jika tidak ada tugas sama sekali, tapi tanggal sudah lewat (selesai)
        return $this->status === 'selesai' ? 100 : 0;
    }

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function panitia()
    {
        return $this->belongsToMany(User::class , 'kegiatan_panitia', 'kegiatan_id', 'user_id')
            ->withPivot('jabatan')
            ->withTimestamps();
    }

    /** Anggota/panitia manual (nama + jabatan) di workspace kegiatan */
    public function anggotaList()
    {
        return $this->hasMany(KegiatanAnggota::class , 'kegiatan_id');
    }

    public function arsips()
    {
        return $this->hasMany(Arsip::class , 'kegiatan_id');
    }
}
