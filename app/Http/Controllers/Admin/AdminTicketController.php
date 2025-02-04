<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class AdminTicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['concert', 'ticketType', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['concert', 'ticketType', 'user']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function scan(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:used,unused'
        ]);

        $ticket->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket status updated successfully',
            'ticket' => $ticket
        ]);
    }

    public function ticketTypes()
    {
        $ticketTypes = TicketType::withCount('tickets')->get();
        return view('admin.tickets.types.index', compact('ticketTypes'));
    }

    public function storeTicketType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1'
        ]);

        $ticketType = TicketType::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ticket type created successfully',
            'data' => $ticketType
        ]);
    }

    public function updateTicketType(Request $request, TicketType $ticketType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1'
        ]);

        $ticketType->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ticket type updated successfully',
            'data' => $ticketType
        ]);
    }

    public function stats()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'tickets_by_status' => [
                'available' => Ticket::where('status', 'available')->count(),
                'sold' => Ticket::where('status', 'sold')->count(),
                'reserved' => Ticket::where('status', 'reserved')->count(),
                'cancelled' => Ticket::where('status', 'cancelled')->count(),
            ],
            'revenue' => Ticket::where('status', 'sold')->sum('price'),
            'recent_sales' => Ticket::where('status', 'sold')
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json($stats);
    }

    public function export()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tickets-' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Concert',
                'Ticket Type',
                'User',
                'Price',
                'Status',
                'Seat Number',
                'Created At',
                'Updated At'
            ]);

            // Query tickets in chunks to handle large datasets
            Ticket::with(['concert', 'ticketType', 'user'])
                ->orderBy('id')
                ->chunk(1000, function($tickets) use ($file) {
                    foreach ($tickets as $ticket) {
                        fputcsv($file, [
                            $ticket->id,
                            $ticket->concert->name ?? 'N/A',
                            $ticket->ticketType->name ?? 'N/A',
                            $ticket->user->name ?? 'N/A',
                            $ticket->price,
                            $ticket->status,
                            $ticket->seat_number ?? 'N/A',
                            $ticket->created_at,
                            $ticket->updated_at
                        ]);
                    }
                });

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
