<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role Sistem: Super Admin
        Role::updateOrCreate(
            ['nama_role' => 'Super Admin'],
            [
                'permissions' => [
                    'dashboard', 'kegiatan', 'tugas', 'keuangan', 'anggota',
                    'dokumentasi', 'arsip', 'pengumuman', 'pengaturan',
                    'manage_users', 'manage_roles', 'keuangan_global',
                ],
            ]
        );

        // Role Sistem: Admin
        Role::updateOrCreate(
            ['nama_role' => 'Admin'],
            [
                'permissions' => [
                    'dashboard', 'kegiatan', 'tugas', 'keuangan', 'anggota',
                    'dokumentasi', 'arsip', 'pengumuman', 'pengaturan',
                    'manage_users',
                ],
            ]
        );

        // Role Sistem: Pengurus
        Role::updateOrCreate(
            ['nama_role' => 'Pengurus'],
            [
                'permissions' => [
                    'dashboard', 'kegiatan', 'tugas', 'keuangan', 'dokumentasi', 'pengumuman',
                ],
            ]
        );

        // Role Sistem: Anggota
        Role::updateOrCreate(
            ['nama_role' => 'Anggota'],
            [
                'permissions' => [
                    'dashboard', 'tugas', 'dokumentasi',
                ],
            ]
        );
    }
}
