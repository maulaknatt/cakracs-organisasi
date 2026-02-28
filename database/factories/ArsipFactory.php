<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArsipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(3),
            'file' => 'mock_document_' . $this->faker->word() . '.pdf',
            'deskripsi' => $this->faker->sentence(),
            'kegiatan_id' => Kegiatan::inRandomOrder()->first()?->id ?? Kegiatan::factory(),
        ];
    }
}