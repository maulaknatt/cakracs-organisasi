<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles
        $this->call(RoleSeeder::class);

        // Create Super Admin user
        $superAdminRole = \App\Models\Role::where('nama_role', 'Super Admin')->first();
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin12345'),
            'role_id' => $superAdminRole->id,
            'jabatan' => 'Ketua',
            'is_active' => true,
        ]);

        // Create Pengurus user (optional)
        $pengurusRole = \App\Models\Role::where('nama_role', 'Pengurus')->first();
        User::factory()->create([
            'name' => 'Pengurus Test',
            'email' => 'pengurus@admin.com',
            'password' => bcrypt('pengurus123'),
            'role_id' => $pengurusRole->id,
            'jabatan' => 'Sekretaris',
            'is_active' => true,
        ]);

        // Create Anggota user (optional)
        $anggotaRole = \App\Models\Role::where('nama_role', 'Anggota')->first();
        User::factory()->create([
            'name' => 'Anggota Test',
            'email' => 'anggota@admin.com',
            'password' => bcrypt('anggota123'),
            'role_id' => $anggotaRole->id,
            'jabatan' => 'Anggota biasa',
            'is_active' => true,
        ]);
    }
}
