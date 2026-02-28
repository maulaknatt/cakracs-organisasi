<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeuanganFactory extends Factory
{
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(3),
            'tanggal' => $this->faker->date(),
            'jenis' => $this->faker->randomElement(['masuk', 'keluar']),
            'deskripsi' => $this->faker->sentence(),
            'jumlah' => $this->faker->numberBetween(10000, 1000000),
            'kegiatan_id' => Kegiatan::inRandomOrder()->first()?->id ?? Kegiatan::factory(),
        ];
    }
}