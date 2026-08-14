<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Set konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Cari booking berdasarkan UUID
        $booking = Booking::where('uuid', $order_id)->first();

        if (!$booking) {
            Log::error('Midtrans Webhook Error: Order ID ' . $order_id . ' tidak ditemukan.');
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $booking->update(['status' => 'pending']);
                } else {
                    if ($booking->status !== 'paid') {
                        $booking->update(['status' => 'paid']);
                        $this->sendSuccessNotification($booking);
                    }
                }
            }
        } else if ($transaction == 'settlement') {
            if ($booking->status !== 'paid') {
                $booking->update(['status' => 'paid']);
                $this->sendSuccessNotification($booking);
            }
        } else if ($transaction == 'pending') {
            $booking->update(['status' => 'pending']);
        } else if ($transaction == 'deny') {
            $booking->update(['status' => 'cancelled']);
        } else if ($transaction == 'expire') {
            $booking->update(['status' => 'cancelled']);
        } else if ($transaction == 'cancel') {
            $booking->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'Payment status updated to ' . $booking->status]);
    }

    private function sendSuccessNotification($booking)
    {
        try {
            $admins = \App\Models\User::whereHas('roles', function($q) {
                $q->whereIn('name', ['super_admin', 'ticketing']);
            })->get();
            
            if ($admins->isEmpty()) {
                $admins = \App\Models\User::all();
            }

            \Filament\Notifications\Notification::make()
                ->title('Pembayaran Berhasil via Midtrans! 🚀')
                ->body("Pesanan tiket dari {$booking->customer_name} telah LUNAS dibayar sejumlah Rp " . number_format($booking->total_price, 0, ',', '.') . ".")
                ->success()
                ->sendToDatabase($admins);
        } catch (\Exception $e) {
            Log::error('Gagal kirim notif: ' . $e->getMessage());
        }
    }
}
