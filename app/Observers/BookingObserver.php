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
        $items = $booking->items()->with('tourPackage')->get();

        foreach ($items as $item) {
            $ticketNumber = 'TIX-' . strtoupper(\Illuminate\Support\Str::random(8));
            
            // Format URL yang akan ada di dalam QR Code
            // URL ini yang discan oleh petugas untuk validasi
            $qrData = route('scanner') . '?ticket=' . $ticketNumber;
            
            // Nama file QR code
            $fileName = 'qrcodes/' . $ticketNumber . '.png';
            $filePath = storage_path('app/public/' . $fileName);
            
            // Pastikan folder qrcodes ada
            if (!file_exists(storage_path('app/public/qrcodes'))) {
                mkdir(storage_path('app/public/qrcodes'), 0755, true);
            }

            // Generate QR Code as PNG image using simple-qrcode
            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(300)
                ->margin(2)
                ->generate($qrData, $filePath);

            $category = $item->category;

            // Create Ticket record
            \App\Models\Ticket::create([
                'booking_id' => $booking->id,
                'ticket_number' => $ticketNumber,
                'category' => $category,
                'participant_count' => $item->quantity,
                'qr_code_path' => $fileName,
                'status' => 'booked',
            ]);
        }
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
