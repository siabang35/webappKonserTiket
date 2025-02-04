<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        // Featured Artists
        $featuredArtists = [
            [
                'name' => 'Taylor Swift',
                'image' => asset('assets/images/artists/Taylor.jfif'),
                'genre' => 'Pop'
            ],
            [
                'name' => 'Ed Sheeran',
                'image' => asset('assets/images/artists/Sheeran.jfif'),
                'genre' => 'Pop/Folk'
            ],
            [
                'name' => 'Coldplay',
                'image' => asset('assets/images/artists/Coldplay.jfif'),
                'genre' => 'Alternative Rock'
            ],
            [
                'name' => 'Imagine Dragons',
                'image' => asset('assets/images/artists/Imagine.jfif'),
                'genre' => 'Alternative/Pop Rock'
            ],
            [
                'name' => 'Raisa',
                'image' => asset('assets/images/artists/Raisa.jfif'),
                'genre' => 'Pop'
            ],
            [
                'name' => 'Tulus',
                'image' => asset('assets/images/artists/Tulus.jfif'),
                'genre' => 'Jazz/Pop'
            ]
        ];

        // Concerts
        $concerts = [
            [
                'id' => 1,
                'title' => 'Taylor Swift Eras Tour',
                'description' => 'Experience the musical journey through all of Taylor\'s eras',
                'date' => Carbon::parse('2024-05-15')->format('l, F j, Y'),
                'time' => '19:00',
                'price' => 2500000,
                'image' => asset('assets/images/artists/Taylor.jfif'),
            ],
            [
                'id' => 2,
                'title' => 'Ed Sheeran Mathematics Tour',
                'description' => 'A night of acoustic perfection with Ed Sheeran',
                'date' => Carbon::parse('2024-06-20')->format('l, F j, Y'),
                'time' => '20:00',
                'price' => 1800000,
                'image' => asset('assets/images/artists/Sheeran.jfif'),
            ],
            [
                'id' => 3,
                'title' => 'Coldplay Music of the Spheres',
                'description' => 'An otherworldly experience with Coldplay',
                'date' => Carbon::parse('2024-07-10')->format('l, F j, Y'),
                'time' => '19:30',
                'price' => 2200000,
                'image' => asset('assets/images/artists/Coldplay.jfif'),
            ],
            [
                'id' => 4,
                'title' => 'Lives Imagine Dragons Concert',
                'description' => 'Limits enegery',
                'date' => Carbon::parse('2024-09-15')->format('l, F j, Y'),
                'time' => '20:30',
                'price' => 2000000,
                'image' => asset('assets/images/artists/Imagine.jfif'),
            ],
            [
                'id' => 5,
                'title' => 'Raisa Live in Jakarta',
                'description' => 'An intimate evening with Raisa and her heartfelt songs',
                'date' => Carbon::parse('2024-08-05')->format('l, F j, Y'),
                'time' => '18:30',
                'price' => 1200000,
                'image' => asset('assets/images/artists/Raisa.jfif'),
            ],
            [
                'id' => 6,
                'title' => 'Tulus Monokrom Tour',
                'description' => 'Enjoy a soulful night with Tulus',
                'date' => Carbon::parse('2024-09-10')->format('l, F j, Y'),
                'time' => '20:00',
                'price' => 1500000,
                'image' => asset('assets/images/artists/Tulus.jfif'),
            ]
        ];

        // Return the landing view with the featured artists and concerts
        return view('landing', compact('featuredArtists', 'concerts'));
    }
}
