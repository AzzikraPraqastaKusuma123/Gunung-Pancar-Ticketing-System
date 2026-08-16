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
            return response()->json(['success' => false, 'message' => 'Tiket sudah digunakan pada ' . $ticket->used_at->format('d M Y H:i')]);
        }

        if ($ticket->status === 'cancelled') {
            return response()->json(['success' => false, 'message' => 'Tiket ini telah dibatalkan.']);
        }

        if ($ticket->booking && $ticket->booking->status !== 'paid') {
            return response()->json(['success' => false, 'message' => 'Pembayaran booking belum dikonfirmasi (status: ' . $ticket->booking->status . ').']);
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

    /**
     * GET - Safe read-only check tanpa mengubah data.
     * Digunakan jika QR code di-scan langsung via browser/link.
     */
    public function validateTicketGet($ticketNumber)
    {
        $ticket = Ticket::with('booking')->where('ticket_number', $ticketNumber)->first();

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Tiket tidak ditemukan.', 'readonly' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tiket ditemukan. Gunakan Scanner Gate untuk melakukan check-in.',
            'readonly' => true,
            'data' => [
                'ticket_number' => $ticket->ticket_number,
                'status' => $ticket->status,
                'customer' => $ticket->booking->customer_name ?? 'N/A',
                'category' => $ticket->category,
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
