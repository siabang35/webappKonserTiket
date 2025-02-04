<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConcertSeeder extends Seeder
{
    public function run()
    {
        DB::table('concerts')->insert([
            [
                'name' => 'Taylor Swift Eras Tour',
                'location' => 'Jakarta',
                'venue' => 'Gelora Bung Karno',
                'date' => '2024-05-15',
                'time' => '19:00:00',
                'genre' => 'Pop',
                'image_url' => 'http://localhost/assets/images/artists/Taylor.jfif',
                'ticket_image' => 'http://localhost/assets/images/artists/Taylor.jfif',
                'tickets_left' => 100,
                'status' => 'upcoming',
                'price' => 2500000,
                'description' => 'Experience the musical journey through all of Taylor\'s eras',
                'artist_id' => 1, // Pastikan sesuai dengan data di tabel artists
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ed Sheeran Mathematics Tour',
                'location' => 'Jakarta',
                'venue' => 'Indonesia Convention Exhibition',
                'date' => '2024-06-20',
                'time' => '20:00:00',
                'genre' => 'Acoustic',
                'image_url' => 'http://localhost/assets/images/artists/Sheeran.jfif',
                'ticket_image' => 'http://localhost/assets/images/artists/Sheeran.jfif',
                'tickets_left' => 100,
                'status' => 'upcoming',
                'price' => 1800000,
                'description' => 'A night of acoustic perfection with Ed Sheeran',
                'artist_id' => 2, // Pastikan sesuai dengan data di tabel artists
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
