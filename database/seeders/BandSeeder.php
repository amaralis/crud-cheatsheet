<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Band;
use App\Models\Album;
use App\Models\Song;

class BandSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numBands = 5;
        $numAlbumsPerBand = 5;
        $numSongsPerAlbum = 5;

        Band::factory()
            ->has(Album::factory()
                ->has(Song::factory()
                    ->count($numSongsPerAlbum))
                ->count($numAlbumsPerBand))
            ->count($numBands)
            ->create();
    }
}
