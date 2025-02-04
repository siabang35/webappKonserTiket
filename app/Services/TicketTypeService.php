<?php

namespace App\Services;

use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Exception;

class TicketTypeService
{
    public function create(array $data): TicketType
    {
        return DB::transaction(function () use ($data) {
            $ticketType = TicketType::create($data);
            $ticketType->createTickets();
            return $ticketType;
        });
    }

    public function update(TicketType $ticketType, array $data): TicketType
    {
        return DB::transaction(function () use ($ticketType, $data) {
            $oldQuantity = $ticketType->quantity;
            $newQuantity = $data['quantity'] ?? $oldQuantity;

            $ticketType->update($data);

            if ($newQuantity > $oldQuantity) {
                $additionalTickets = $newQuantity - $oldQuantity;
                $ticketType->quantity = $additionalTickets;
                $ticketType->createTickets();
                $ticketType->quantity = $newQuantity;
                $ticketType->save();
            }

            return $ticketType->fresh();
        });
    }

    public function findOrFail(int $id): TicketType
    {
        return TicketType::findOrFail($id);
    }

    public function getAvailableTypes(int $concertId): array
    {
        return TicketType::where('concert_id', $concertId)
                        ->onSale()
                        ->with(['tickets' => function ($query) {
                            $query->available();
                        }])
                        ->get()
                        ->map(function ($type) {
                            return [
                                'id' => $type->id,
                                'name' => $type->name,
                                'price' => $type->price,
                                'available_quantity' => $type->available_quantity,
                                'benefits' => $type->benefits,
                                'sale_ends_at' => $type->sale_ends_at,
                                'max_per_transaction' => $type->max_per_transaction
                            ];
                        })
                        ->toArray();
    }

    public function validatePurchase(int $ticketTypeId, int $quantity): array
    {
        try {
            $ticketType = $this->findOrFail($ticketTypeId);

            if (!$ticketType->is_on_sale) {
                throw new Exception('Tickets are not on sale');
            }

            if (!$ticketType->validatePurchaseQuantity($quantity)) {
                throw new Exception('Invalid purchase quantity');
            }

            return [
                'success' => true,
                'ticket_type' => $ticketType,
                'quantity' => $quantity,
                'total_price' => $ticketType->price * $quantity
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getSalesStats(int $concertId): array
    {
        return TicketType::where('concert_id', $concertId)
                        ->withCount(['tickets as total_tickets'])
                        ->withCount(['tickets as sold_tickets' => function ($query) {
                            $query->where('status', 'sold');
                        }])
                        ->withCount(['tickets as reserved_tickets' => function ($query) {
                            $query->where('status', 'reserved');
                        }])
                        ->get()
                        ->map(function ($type) {
                            return [
                                'name' => $type->name,
                                'total' => $type->total_tickets,
                                'sold' => $type->sold_tickets,
                                'reserved' => $type->reserved_tickets,
                                'available' => $type->total_tickets - $type->sold_tickets - $type->reserved_tickets,
                                'revenue' => $type->sold_tickets * $type->price
                            ];
                        })
                        ->toArray();
    }
}
