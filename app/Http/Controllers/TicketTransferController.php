<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketTransfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketTransferController extends Controller
{
    public function initiate(Request $request, Ticket $ticket)
    {
        $request->validate([
            'to_email' => 'required|email|exists:users,email'
        ]);

        // Check if ticket belongs to authenticated user
        if ($ticket->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to transfer this ticket'
            ], 403);
        }

        // Check if ticket is transferable (not used or cancelled)
        if (!in_array($ticket->status, ['sold', 'reserved'])) {
            return response()->json([
                'success' => false,
                'message' => 'This ticket cannot be transferred'
            ], 422);
        }

        // Get recipient user
        $toUser = User::where('email', $request->to_email)->first();

        // Check for existing pending transfers
        $existingTransfer = TicketTransfer::where('ticket_id', $ticket->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($existingTransfer) {
            return response()->json([
                'success' => false,
                'message' => 'There is already a pending transfer for this ticket'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create transfer record
            $transfer = TicketTransfer::create([
                'ticket_id' => $ticket->id,
                'from_user_id' => auth()->id(),
                'to_user_id' => $toUser->id,
                'status' => 'pending',
                'transfer_code' => Str::random(32),
                'expires_at' => Carbon::now()->addHours(24)
            ]);

            // Update ticket status
            $ticket->update(['status' => 'reserved']);

            DB::commit();

            // TODO: Send email notification to recipient

            return response()->json([
                'success' => true,
                'message' => 'Transfer initiated successfully',
                'data' => [
                    'transfer_code' => $transfer->transfer_code,
                    'expires_at' => $transfer->expires_at
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket transfer initiation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate transfer'
            ], 500);
        }
    }

    public function show($code)
    {
        $transfer = TicketTransfer::where('transfer_code', $code)
            ->with(['ticket.concert', 'ticket.ticketType', 'fromUser', 'toUser'])
            ->firstOrFail();

        if ($transfer->status !== 'pending') {
            return view('tickets.transfer.invalid', [
                'message' => 'This transfer has already been ' . $transfer->status
            ]);
        }

        if ($transfer->expires_at < Carbon::now()) {
            return view('tickets.transfer.invalid', [
                'message' => 'This transfer has expired'
            ]);
        }

        return view('tickets.transfer.show', compact('transfer'));
    }

    public function complete(Request $request, $code)
    {
        $transfer = TicketTransfer::where('transfer_code', $code)
            ->where('status', 'pending')
            ->where('expires_at', '>', Carbon::now())
            ->firstOrFail();

        // Verify the user is the intended recipient
        if ($transfer->to_user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to complete this transfer'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Update ticket ownership
            $transfer->ticket->update([
                'user_id' => $transfer->to_user_id,
                'status' => 'sold'
            ]);

            // Complete transfer
            $transfer->update([
                'status' => 'completed',
                'completed_at' => Carbon::now()
            ]);

            DB::commit();

            // TODO: Send confirmation emails to both parties

            return response()->json([
                'success' => true,
                'message' => 'Transfer completed successfully',
                'data' => [
                    'ticket' => $transfer->ticket,
                    'transfer' => $transfer
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket transfer completion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete transfer'
            ], 500);
        }
    }
}
