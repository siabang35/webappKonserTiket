<?php

namespace App\Services;

use App\Models\Concert;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;

class ConcertService
{
    public function createConcert(array $data)
{
    return DB::transaction(function () use ($data) {
        // Upload images if provided
        $imageService = new ImageService();
        $imageUrl = isset($data['image_url']) ? $imageService->uploadImage($data['image_url'], 'concert_images') : null;
        $ticketImage = isset($data['ticket_image']) ? $imageService->uploadImage($data['ticket_image'], 'ticket_images') : null;

        // Create concert with all fields
        $concert = Concert::create([
            'name' => $data['name'],
            'artist_id' => $data['artist_id'],
            'description' => $data['description'] ?? null,
            'venue' => $data['venue'] ?? null,
            'date' => $data['date'],
            'time' => $data['time'] ?? null,
            'image_url' => $imageUrl,
            'ticket_image' => $ticketImage,
            'status' => $data['status'],
            'ticket_type' => $data['ticket_type'],
            'price' => $data['price'],
            'is_promotion' => $data['is_promotion'] ?? false,
            'promotion_price' => $data['promotion_price'] ?? null,
            'location' => $data['location'],
            'genre' => $data['genre'] ?? null,
            'tickets_left' => $data['tickets_left']
        ]);

        return $concert;
    });
}

    public function createTicketTypes(Concert $concert, array $ticketTypes)
    {
        $totalTickets = 0;

        foreach ($ticketTypes as $type) {
            $ticketType = $concert->ticketTypes()->create([
                'name' => $type['name'],
                'price' => $type['price'],
                'quantity' => $type['quantity'],
                'description' => $type['description'] ?? null
            ]);

            $ticketType->createTickets();
            $totalTickets += $type['quantity'];
        }

        $concert->update(['tickets_left' => $totalTickets]);
    }

    public function updateConcert(Concert $concert, array $data)
    {
        return DB::transaction(function () use ($concert, $data) {
            $updateData = [
                'name' => $data['name'],
                'artist_id' => $data['artist_id'],
                'description' => $data['description'] ?? $concert->description,
                'venue' => $data['venue'] ?? $concert->venue,
                'date' => $data['date'],
                'time' => $data['time'] ?? $concert->time,
                'status' => $data['status'],
                'ticket_type' => $data['ticket_type'],
                'price' => $data['price'],
                'location' => $data['location'],
                'genre' => $data['genre'] ?? $concert->genre,
                'tickets_left' => $data['tickets_left'],
                'is_promotion' => $data['is_promotion'] ?? $concert->is_promotion
            ];

            // Only update images if new ones are provided
            if (isset($data['image_url'])) {
                $updateData['image_url'] = $data['image_url'];
            }
            if (isset($data['ticket_image'])) {
                $updateData['ticket_image'] = $data['ticket_image'];
            }

            // Only update promotion price if is_promotion is true
            if ($updateData['is_promotion']) {
                $updateData['promotion_price'] = $data['promotion_price'] ?? $concert->promotion_price;
            } else {
                $updateData['promotion_price'] = null;
            }

            $concert->update($updateData);
            return $concert;
        });
    }

    public function updateTicketTypes(Concert $concert, array $ticketTypes)
    {
        return DB::transaction(function () use ($concert, $ticketTypes) {
            foreach ($ticketTypes as $type) {
                if (isset($type['id'])) {
                    $ticketType = TicketType::find($type['id']);
                    if ($ticketType) {
                        $ticketType->update([
                            'name' => $type['name'],
                            'price' => $type['price'],
                            'description' => $type['description'] ?? null
                        ]);
                    }
                } else {
                    $ticketType = $concert->ticketTypes()->create([
                        'name' => $type['name'],
                        'price' => $type['price'],
                        'quantity' => $type['quantity'],
                        'description' => $type['description'] ?? null
                    ]);
                    $ticketType->createTickets();
                }
            }

            // Update total tickets left
            $concert->update([
                'tickets_left' => $concert->ticketTypes()
                    ->withSum('tickets', 'status = "available"')
                    ->get()
                    ->sum('tickets_sum')
            ]);
        });
    }

    public function deleteConcert(Concert $concert)
    {
        return DB::transaction(function () use ($concert) {
            $concert->ticketTypes()->delete();
            $concert->tickets()->delete();
            $concert->delete();
        });
    }
}
