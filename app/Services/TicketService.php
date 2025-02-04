<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class TicketService
{
    public function generateTicket(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $ticket = Ticket::create($data);
            $ticket->generateTicketCode();
            return $ticket;
        });
    }

    public function reserveTickets(int $ticketTypeId, int $quantity): array
    {
        return DB::transaction(function () use ($ticketTypeId, $quantity) {
            $ticketType = app(TicketTypeService::class)->findOrFail($ticketTypeId);
            return $ticketType->reserveTickets($quantity);
        });
    }

    public function sellTickets(array $reservedTickets, User $user): bool
    {
        return DB::transaction(function () use ($reservedTickets, $user) {
            $success = true;
            foreach ($reservedTickets as $ticket) {
                if (!$ticket->sell($user)) {
                    $success = false;
                    break;
                }
            }
            return $success;
        });
    }

    public function scanTicket(string $ticketCode): array
    {
        try {
            $ticket = Ticket::where('ticket_code', $ticketCode)
                          ->with(['concert', 'ticketType', 'user'])
                          ->firstOrFail();

            $scanned = $ticket->scan();

            return [
                'success' => $scanned,
                'ticket' => $ticket,
                'message' => $scanned ? 'Ticket successfully scanned' : 'Invalid ticket'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Ticket not found'
            ];
        }
    }

    public function initiateTransfer(Ticket $ticket, User $newUser): array
    {
        try {
            DB::beginTransaction();

            if (!$ticket->can_be_transferred) {
                throw new Exception('Ticket cannot be transferred');
            }

            $transfer = TicketTransfer::create([
                'ticket_id' => $ticket->id,
                'from_user_id' => $ticket->user_id,
                'to_user_id' => $newUser->id,
                'status' => TicketTransfer::STATUS_PENDING,
                'transfer_code' => strtoupper(Str::random(8)),
                'expires_at' => now()->addHours(24)
            ]);

            DB::commit();

            return [
                'success' => true,
                'transfer' => $transfer,
                'message' => 'Transfer initiated successfully'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function completeTransfer(string $transferCode): array
    {
        try {
            DB::beginTransaction();

            $transfer = TicketTransfer::where('transfer_code', $transferCode)
                                    ->where('status', TicketTransfer::STATUS_PENDING)
                                    ->where('expires_at', '>', now())
                                    ->firstOrFail();

            $ticket = $transfer->ticket;
            $ticket->update([
                'user_id' => $transfer->to_user_id,
                'metadata' => array_merge($ticket->metadata ?? [], [
                    'transferred_at' => now()->toIso8601String(),
                    'transfer_id' => $transfer->id
                ])
            ]);

            $transfer->update([
                'status' => TicketTransfer::STATUS_COMPLETED,
                'completed_at' => now()
            ]);

            DB::commit();

            return [
                'success' => true,
                'ticket' => $ticket,
                'message' => 'Transfer completed successfully'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Invalid or expired transfer code'
            ];
        }
    }

    public function validateTicket(string $ticketCode): array
    {
        try {
            $ticket = Ticket::where('ticket_code', $ticketCode)
                          ->with(['concert', 'ticketType', 'user'])
                          ->firstOrFail();

            return [
                'success' => true,
                'valid' => $ticket->is_valid,
                'ticket' => $ticket,
                'message' => $ticket->is_valid ? 'Valid ticket' : 'Invalid ticket'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'valid' => false,
                'message' => 'Ticket not found'
            ];
        }
    }
}
