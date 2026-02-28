<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

try {
    $role = Role::firstOrCreate(
        ['nama_role' => 'Super Admin'],
        [
            'permissions' => [
                'dashboard',
                'kegiatan',
                'tugas',
                'keuangan',
                'anggota',
                'dokumentasi',
                'arsip',
                'pengumuman',
                'pengaturan',
                'manage_users',
                'manage_roles',
                'keuangan_global',
            ]
        ]
    );

    $existing = User::where('email', 'maulanabagus565@gmail.com')->first();
    if ($existing) {
        $existing->update([
            'name' => 'Maule',
            'password' => Hash::make('Maulana565@'),
            'role_id' => $role->id,
            'jabatan' => 'Dewa',
            'is_active' => 1
        ]);
        echo "User 'Maule' updated successfully. ID: " . $existing->id . "\n";
    }
    else {
        $user = User::create([
            'name' => 'Maule',
            'email' => 'maulanabagus565@gmail.com',
            'password' => Hash::make('Maulana565@'),
            'role_id' => $role->id,
            'jabatan' => 'Dewa',
            'is_active' => 1
        ]);
        echo "User 'Maule' created successfully. ID: " . $user->id . "\n";
    }
}
catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
