<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Services\TicketService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $packagesDb = \App\Models\TourPackage::where('is_active', true)->get()->keyBy('name');
        return view('booking', compact('packagesDb'));
    }

    public function store(Request $request, TicketService $ticketService)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'visit_date' => 'required|date|after_or_equal:today',
            'qty_dewasa' => 'required|integer|min:0',
            'qty_anak' => 'required|integer|min:0',
            'qty_group' => 'required|integer|min:0',
            'qty_pancar_trek' => 'required|integer|min:0',
            'qty_pancar_school' => 'required|integer|min:0',
            'qty_prewedding' => 'required|integer|min:0',
            'qty_foto_produk' => 'required|integer|min:0',
            'qty_shooting' => 'required|integer|min:0',
            'participants_dewasa' => 'array',
            'participants_dewasa.*' => 'string|max:255',
            'participants_anak' => 'array',
            'participants_anak.*' => 'string|max:255',
            'participants_group' => 'array',
            'participants_group.*' => 'string|max:255',
            'participants_pancar_trek' => 'array',
            'participants_pancar_trek.*' => 'string|max:255',
            'participants_pancar_school' => 'array',
            'participants_pancar_school.*' => 'string|max:255',
            'participants_prewedding' => 'array',
            'participants_prewedding.*' => 'string|max:255',
            'participants_foto_produk' => 'array',
            'participants_foto_produk.*' => 'string|max:255',
            'participants_shooting' => 'array',
            'participants_shooting.*' => 'string|max:255',
        ]);

        $totalQty = $validated['qty_dewasa'] + $validated['qty_anak'] + $validated['qty_group'] + 
                    $validated['qty_pancar_trek'] + $validated['qty_pancar_school'] + 
                    $validated['qty_prewedding'] + $validated['qty_foto_produk'] + $validated['qty_shooting'];
        if ($totalQty == 0) {
            return back()->with('error', 'Minimal pilih 1 tiket!');
        }

        // Fetch dari Database
        $packagesDb = \App\Models\TourPackage::where('is_active', true)->get()->keyBy('name');

        // Harga Paket
        $hargaDewasa = $packagesDb['Tiket Dewasa']->base_price ?? 50000;
        $hargaAnak = $packagesDb['Tiket Anak']->base_price ?? 25000;
        $hargaGroup = $packagesDb['Paket Group']->base_price ?? 200000; // Paket isi 5 orang
        $hargaPancarTrek = $packagesDb['Pancar Trek']->base_price ?? 165000;
        $hargaPancarSchool = $packagesDb['Pancar School']->base_price ?? 125000;
        $hargaPrewedding = $packagesDb['Prewedding / Wedding Photo']->base_price ?? 750000;
        $hargaFotoProduk = $packagesDb['Foto Produk']->base_price ?? 7500000;
        $hargaShooting = $packagesDb['Shooting Komersial']->base_price ?? 20000000;

        $totalPrice = ($validated['qty_dewasa'] * $hargaDewasa) + 
                      ($validated['qty_anak'] * $hargaAnak) + 
                      ($validated['qty_group'] * $hargaGroup) +
                      ($validated['qty_pancar_trek'] * $hargaPancarTrek) +
                      ($validated['qty_pancar_school'] * $hargaPancarSchool) +
                      ($validated['qty_prewedding'] * $hargaPrewedding) +
                      ($validated['qty_foto_produk'] * $hargaFotoProduk) +
                      ($validated['qty_shooting'] * $hargaShooting);

        $booking = Booking::create([
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'booking_date' => now(),
            'visit_date' => $validated['visit_date'],
            'total_price' => $totalPrice,
            'status' => 'pending', // Ubah ke pending
        ]);

        // Kirim Notifikasi Pesanan Baru (Pending) ke Admin
        try {
            $admins = \App\Models\User::whereHas('roles', function($q) {
                $q->whereIn('name', ['super_admin', 'ticketing']);
            })->get();
            
            if ($admins->isEmpty()) { 
                $admins = \App\Models\User::all(); 
            }
            
            \Filament\Notifications\Notification::make()
                ->title('Pesanan Baru Masuk 🔔')
                ->body("{$booking->customer_name} sedang dalam proses pembayaran untuk tanggal kunjungan " . \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') . ".")
                ->info()
                ->sendToDatabase($admins);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Notif Error: ' . $e->getMessage());
        }

        if ($validated['qty_dewasa'] > 0) {
            BookingItem::create(['booking_id' => $booking->id, 'category' => 'dewasa', 'quantity' => $validated['qty_dewasa'], 'price_per_item' => $hargaDewasa]);
        }
        if ($validated['qty_anak'] > 0) {
            BookingItem::create(['booking_id' => $booking->id, 'category' => 'anak-anak', 'quantity' => $validated['qty_anak'], 'price_per_item' => $hargaAnak]);
        }
        if ($validated['qty_group'] > 0) {
            BookingItem::create(['booking_id' => $booking->id, 'category' => 'group', 'quantity' => $validated['qty_group'], 'price_per_item' => $hargaGroup]);
        }
        if ($validated['qty_pancar_trek'] > 0) {
            BookingItem::create(['booking_id' => $booking->id, 'category' => 'pancar_trek', 'quantity' => $validated['qty_pancar_trek'], 'price_per_item' => $hargaPancarTrek]);
        }
        if ($validated['qty_pancar_school'] > 0) {
            BookingItem::create(['booking_id' => $booking->id, 'category' => 'pancar_school', 'quantity' => $validated['qty_pancar_school'], 'price_per_item' => $hargaPancarSchool]);
        }
        if ($validated['qty_prewedding'] > 0) {
            BookingItem::create(['booking_id' => $booking->id, 'category' => 'prewedding', 'quantity' => $validated['qty_prewedding'], 'price_per_item' => $hargaPrewedding]);
        }
        if ($validated['qty_foto_produk'] > 0) {
            BookingItem::create(['booking_id' => $booking->id, 'category' => 'foto_produk', 'quantity' => $validated['qty_foto_produk'], 'price_per_item' => $hargaFotoProduk]);
        }
        if ($validated['qty_shooting'] > 0) {
            BookingItem::create(['booking_id' => $booking->id, 'category' => 'shooting', 'quantity' => $validated['qty_shooting'], 'price_per_item' => $hargaShooting]);
        }

        // Generate tickets & QR Codes with participant names
        $participantsData = [
            'dewasa' => $request->input('participants_dewasa', []),
            'anak-anak' => $request->input('participants_anak', []),
            'group' => $request->input('participants_group', []),
            'pancar_trek' => $request->input('participants_pancar_trek', []),
            'pancar_school' => $request->input('participants_pancar_school', []),
            'prewedding' => $request->input('participants_prewedding', []),
            'foto_produk' => $request->input('participants_foto_produk', []),
            'shooting' => $request->input('participants_shooting', []),
        ];
        $ticketService->generateTicketsForBooking($booking, $participantsData);

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $booking->uuid, // Menggunakan UUID agar unik
                'gross_amount' => (int) $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $validated['customer_name'],
                'email' => $validated['customer_email'],
                'phone' => $validated['customer_phone'],
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $booking->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Error: ' . $e->getMessage());
        }

        return redirect()->route('invoice', $booking->uuid)->with('success', 'Booking berhasil dibuat!');
    }

    public function invoice($uuid)
    {
        $booking = Booking::with('tickets')->where('uuid', $uuid)->firstOrFail();
        
        // Pengecekan status Midtrans langsung jika status masih pending
        // Ini berguna terutama di local/tanpa webhook ngrok
        if ($booking->status === 'pending') {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            
            try {
                $status = \Midtrans\Transaction::status($booking->uuid);
                if ($status->transaction_status == 'capture' || $status->transaction_status == 'settlement') {
                    $booking->update(['status' => 'paid']);
                    
                    // Notif Lunas
                    try {
                        $admins = \App\Models\User::whereHas('roles', function($q) {
                            $q->whereIn('name', ['super_admin', 'ticketing']);
                        })->get();
                        if ($admins->isEmpty()) { $admins = \App\Models\User::all(); }

                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran Berhasil! 🚀')
                            ->body("Pesanan tiket dari {$booking->customer_name} telah LUNAS dibayar sejumlah Rp " . number_format($booking->total_price, 0, ',', '.') . ".")
                            ->success()
                            ->sendToDatabase($admins);
                    } catch (\Exception $e) {}
                    
                } elseif (in_array($status->transaction_status, ['deny', 'cancel', 'expire'])) {
                    $booking->update(['status' => 'cancelled']);
                }
            } catch (\Exception $e) {
                // Ignore jika transaksi belum ada di Midtrans
            }
        }

        return view('invoice', compact('booking'));
    }

    public function downloadPdf($uuid)
    {
        $booking = Booking::with('tickets')->where('uuid', $uuid)->firstOrFail();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.ticket', compact('booking'));
        
        // Ubah status tiket jadi printed
        foreach ($booking->tickets as $ticket) {
            if ($ticket->status === 'booked') {
                $ticket->update(['status' => 'printed']);
            }
        }
        
        return $pdf->download('Tiket-Camping-' . $booking->customer_name . '.pdf');
    }

    public function posPrint($uuid)
    {
        $booking = Booking::with('tickets', 'items.tourPackage')->where('uuid', $uuid)->firstOrFail();
        
        // Ubah status tiket jadi printed otomatis
        foreach ($booking->tickets as $ticket) {
            if ($ticket->status === 'booked') {
                $ticket->update(['status' => 'printed']);
            }
        }
        
        return view('tickets.print-pos', compact('booking'));
    }
}
