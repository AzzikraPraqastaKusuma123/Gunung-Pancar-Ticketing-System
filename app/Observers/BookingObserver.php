<?php

namespace App\Observers;

use App\Models\Booking;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Jika status berubah menjadi 'paid'
        if ($booking->isDirty('status') && $booking->status === 'paid') {
            // Cek apakah tiket belum pernah dibuat untuk booking ini
            if ($booking->tickets()->count() === 0) {
                $this->generateTickets($booking);
            }
        }
    }

    protected function generateTickets(Booking $booking)
    {
        $ticketService = app(\App\Services\TicketService::class);
        $ticketService->generateTicketsForBooking($booking);
    }
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}
