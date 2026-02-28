<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class DokumentasiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kegiatan_id' => Kegiatan::inRandomOrder()->first()?->id ?? Kegiatan::factory(),
            'judul' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph(),
            'file' => 'mock_image_' . $this->faker->word() . '.jpg',
            'highlight' => $this->faker->boolean(20),
        ];
    }
}