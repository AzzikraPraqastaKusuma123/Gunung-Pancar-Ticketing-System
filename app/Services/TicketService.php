<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    /**
     * Generate tickets for a booking based on its items.
     */
    public function generateTicketsForBooking(Booking $booking, array $participantsData = [])
    {
        foreach ($booking->items as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                $ticketNumber = $this->generateUniqueTicketNumber();
                
                // Ambil nama dari array berdasarkan kategori dan index
                $participantName = null;
                if (isset($participantsData[$item->category]) && isset($participantsData[$item->category][$i])) {
                    $participantName = $participantsData[$item->category][$i];
                }

                $ticket = Ticket::create([
                    'ticket_number' => $ticketNumber,
                    'booking_id' => $booking->id,
                    'category' => $item->category,
                    'participant_name' => $participantName,
                    'participant_count' => ($item->category === 'group') ? 5 : 1,
                    'status' => 'booked',
                ]);

                // Generate QR Code
                $qrContent = config('app.url') . '/api/tickets/validate/' . $ticketNumber;
                $qrImage = QrCode::format('svg')->size(300)->generate($qrContent);
                
                $qrPath = 'qrcodes/' . $ticketNumber . '.svg';
                Storage::disk('public')->put($qrPath, $qrImage);

                $ticket->update(['qr_code_path' => $qrPath]);
            }
        }
    }

    /**
     * Generate a unique ticket number.
     */
    private function generateUniqueTicketNumber(): string
    {
        do {
            $prefix = 'CMPG-';
            $date = date('Ymd');
            $randomString = strtoupper(Str::random(6));
            $ticketNumber = $prefix . $date . '-' . $randomString;
        } while (Ticket::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }
}
