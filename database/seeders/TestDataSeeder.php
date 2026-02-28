<?php

namespace Database\Seeders;

use App\Models\Arsip;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use App\Models\Keuangan;
use App\Models\Pengumuman;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Matikan foreign key checks agar bisa truncate
        Schema::disableForeignKeyConstraints();

        // 1. Bersihkan data lama
        Pengumuman::truncate();
        Tugas::truncate();
        Keuangan::truncate();
        Dokumentasi::truncate();
        Arsip::truncate();
        Kegiatan::truncate();

        // Hidupkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // 2. Pastikan ada setidaknya beberapa user
        if (User::count() < 5) {
            User::factory(5)->create();
        }

        // 3. Pengumuman (Tepat 30 data)
        Pengumuman::factory(30)->create();

        // 4. Kegiatan (Tepat 30 data sesuai permintaan)
        $kegiatans = Kegiatan::factory(30)->create();

        // 5. Modul yang bergantung pada Kegiatan
        // Menerapkan tepat 30 untuk masing-masing modul

        // Tugas (30 data)
        Tugas::factory(30)->create([
            'kegiatan_id' => function () use ($kegiatans) {
            return $kegiatans->random()->id;
        }
        ]);

        // Keuangan (30 data)
        Keuangan::factory(30)->create([
            'kegiatan_id' => function () use ($kegiatans) {
            return $kegiatans->random()->id;
        }
        ]);

        // Dokumentasi (30 data)
        Dokumentasi::factory(30)->create([
            'kegiatan_id' => function () use ($kegiatans) {
            return $kegiatans->random()->id;
        }
        ]);

        // Arsip (30 data)
        Arsip::factory(30)->create([
            'kegiatan_id' => function () use ($kegiatans) {
            return $kegiatans->random()->id;
        }
        ]);
    }
}
