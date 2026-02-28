<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VoiceChannel;
use App\Models\Soundboard;

class VoiceChannelSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        VoiceChannel::truncate();

        VoiceChannel::create([
            'name' => 'Nongki Online',
            'slug' => 'nongki-online',
            'icon' => 'mic',
            'sort_order' => 1
        ]);

        VoiceChannel::create([
            'name' => 'Diskusi Tim',
            'slug' => 'diskusi-tim',
            'icon' => 'mic',
            'sort_order' => 2
        ]);

        VoiceChannel::create([
            'name' => 'Meeting Pengurus',
            'slug' => 'meeting-pengurus',
            'icon' => 'mic',
            'sort_order' => 3
        ]);

        // Soundboards tetap ada tapi pastikan datanya bersih
        Soundboard::truncate();
        Soundboard::create([
            'name' => 'Ketawa',
            'icon' => '😂',
            'file_path' => '/sounds/laugh.mp3',
            'sort_order' => 1
        ]);
        Soundboard::create([
            'name' => 'Drum Roll',
            'icon' => '🥁',
            'file_path' => '/sounds/drumroll.mp3',
            'sort_order' => 2
        ]);
        Soundboard::create([
            'name' => 'Zonk',
            'icon' => '👎',
            'file_path' => '/sounds/zonk.mp3',
            'sort_order' => 3
        ]);
    }
}
