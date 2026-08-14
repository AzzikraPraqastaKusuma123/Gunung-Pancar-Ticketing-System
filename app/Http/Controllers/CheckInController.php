<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function scanner()
    {
        return view('scanner');
    }

    public function validateTicket($ticketNumber)
    {
        $ticket = Ticket::with('booking')->where('ticket_number', $ticketNumber)->first();

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Tiket tidak ditemukan.']);
        }

        if ($ticket->status === 'used') {
            return response()->json(['success' => false, 'message' => 'Tiket sudah digunakan pada ' . $ticket->used_at]);
        }

        if ($ticket->status === 'cancelled') {
            return response()->json(['success' => false, 'message' => 'Tiket ini telah dibatalkan.']);
        }

        // Tandai sebagai used
        $ticket->update([
            'status' => 'used',
            'scanned_by_user_id' => auth()->id(),
            'used_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket Valid! Check-in berhasil.',
            'data' => [
                'customer' => $ticket->booking->customer_name,
                'category' => $ticket->category,
                'pax' => $ticket->participant_count
            ]
        ]);
    }

    public function printTicket(\App\Models\Ticket $ticket)
    {
        // Update status tiket jadi printed jika belum used
        if ($ticket->status === 'booked') {
            $ticket->update(['status' => 'printed']);
        }

        return view('tickets.print', compact('ticket'));
    }

    public function printManualTicket(\App\Models\Ticket $ticket)
    {
        // Update status tiket jadi printed jika belum used
        if ($ticket->status === 'booked') {
            $ticket->update(['status' => 'printed']);
        }

        return view('tickets.print-manual', compact('ticket'));
    }
}
