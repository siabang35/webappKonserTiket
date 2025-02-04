<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedArtist extends Model
{
    use HasFactory;
    // Relasi dengan Concert
 public function concerts()
 {
     return $this->hasMany(Concert::class);
 }

    protected $fillable = ['name', 'genre', 'image'];
}


