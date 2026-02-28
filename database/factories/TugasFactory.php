<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TugasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(4),
            'deskripsi' => $this->faker->text(200),
            'penanggung_jawab' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'deadline' => $this->faker->dateTimeBetween('now', '+2 weeks'),
            'status' => $this->faker->randomElement(['Pending', 'Progress', 'Selesai']),
            'kegiatan_id' => Kegiatan::inRandomOrder()->first()?->id ?? Kegiatan::factory(),
        ];
    }
}