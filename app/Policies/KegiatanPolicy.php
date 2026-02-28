<?php

namespace App\Policies;

use App\Models\Kegiatan;
use App\Models\User;

class KegiatanPolicy
{
    /**
     * Determine whether the user can view any models.
     * Semua anggota boleh melihat daftar kegiatan.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model (buka workspace).
     * Semua anggota boleh membuka workspace kegiatan (read-only untuk Anggota).
     */
    public function view(User $user, Kegiatan $kegiatan): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isPengurus();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kegiatan $kegiatan): bool
    {
        return $user->isSuperAdmin() || $user->isPengurus();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kegiatan $kegiatan): bool
    {
        return $user->isSuperAdmin();
    }
}
