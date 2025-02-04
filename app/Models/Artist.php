<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_url',
        'biography',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function concerts()
    {
        return $this->hasMany(Concert::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
