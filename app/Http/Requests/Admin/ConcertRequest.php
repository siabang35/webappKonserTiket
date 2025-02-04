<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ConcertRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'artist_id' => 'required|exists:artists,id',
        'location' => 'required|string|max:255',
        'venue' => 'nullable|string|max:255',
        'date' => 'required|date',
        'time' => 'nullable|date_format:H:i',
        'genre' => 'nullable|string|max:255',
        'image_url' => 'nullable|image|max:2048',  // Validasi untuk gambar konser
        'ticket_image' => 'nullable|image|max:2048',  // Validasi untuk gambar tiket
        'tickets_left' => 'required|integer|min:0',
        'status' => 'required|in:upcoming,limited',
        'ticket_type' => 'required|in:reguler,vip',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'is_promotion' => 'nullable|boolean',
        'promotion_price' => 'nullable|required_if:is_promotion,1|numeric|min:0|lt:price',
        'ticket_types' => 'nullable|array',  // Validasi untuk array tipe tiket
        'ticket_types.*.name' => 'required|string|max:255',
        'ticket_types.*.price' => 'required|numeric|min:0',
        'ticket_types.*.quantity' => 'required|integer|min:1',
    ];
}
}
