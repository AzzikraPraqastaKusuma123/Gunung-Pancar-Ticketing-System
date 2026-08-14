<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Services\TicketService;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class RegistrasiOffline extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string|\UnitEnum|null $navigationGroup = 'Pemesanan Tiket';
    protected static ?string $navigationLabel = 'Registrasi';
    protected static ?string $title = 'Registrasi Tiket';
    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.registrasi-offline';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'customer_name' => 'Walk-in Guest',
            'qty_group' => 0,
            'qty_pancar_trek' => 0,
            'qty_pancar_school' => 0,
            'qty_prewedding' => 0,
            'qty_foto_produk' => 0,
            'qty_shooting' => 0,
            'payment_method' => 'cash',
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Informasi Pengunjung')
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Nama Pengunjung')
                            ->required(),
                        TextInput::make('customer_phone')
                            ->label('No. WhatsApp (Opsional)')
                            ->tel(),
                    ])->columns(2),

                Section::make('Kuantitas Tiket')
                    ->schema([
                        TextInput::make('qty_group')
                            ->label('Group 5 Orang (Rp 200.000)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_pancar_trek')
                            ->label('Pancar Trek (Rp 165.000)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_pancar_school')
                            ->label('Pancar School (Rp 125.000)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_prewedding')
                            ->label('Prewedding (Rp 750.000)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_foto_produk')
                            ->label('Foto Produk (Rp 7.500.000)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_shooting')
                            ->label('Shooting Komersial (Rp 20.000.000)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                    ])->columns(3),

                Section::make('Pembayaran')
                    ->schema([
                        Radio::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Tunai (Cash)',
                                'midtrans' => 'Midtrans (QRIS / Transfer Bank)',
                            ])
                            ->inline()
                            ->required()
                            ->default('cash'),
                    ]),
            ])
            ->statePath('data');
    }

    public function getHargaTotalProperty(): int
    {
        $group = (int) ($this->data['qty_group'] ?? 0);
        $pancar_trek = (int) ($this->data['qty_pancar_trek'] ?? 0);
        $pancar_school = (int) ($this->data['qty_pancar_school'] ?? 0);
        $prewedding = (int) ($this->data['qty_prewedding'] ?? 0);
        $foto_produk = (int) ($this->data['qty_foto_produk'] ?? 0);
        $shooting = (int) ($this->data['qty_shooting'] ?? 0);

        $packagesDb = \App\Models\TourPackage::where('is_active', true)->get()->keyBy('name');

        return ($group * ($packagesDb['Paket Group']->base_price ?? 200000)) +
               ($pancar_trek * ($packagesDb['Pancar Trek']->base_price ?? 165000)) + 
               ($pancar_school * ($packagesDb['Pancar School']->base_price ?? 125000)) +
               ($prewedding * ($packagesDb['Prewedding / Wedding Photo']->base_price ?? 750000)) + 
               ($foto_produk * ($packagesDb['Foto Produk']->base_price ?? 7500000)) + 
               ($shooting * ($packagesDb['Shooting Komersial']->base_price ?? 20000000));
    }

    public function getJumlahTiketProperty(): int
    {
        $group = (int) ($this->data['qty_group'] ?? 0);
        $pancar_trek = (int) ($this->data['qty_pancar_trek'] ?? 0);
        $pancar_school = (int) ($this->data['qty_pancar_school'] ?? 0);
        $prewedding = (int) ($this->data['qty_prewedding'] ?? 0);
        $foto_produk = (int) ($this->data['qty_foto_produk'] ?? 0);
        $shooting = (int) ($this->data['qty_shooting'] ?? 0);

        return $group + $pancar_trek + $pancar_school + $prewedding + $foto_produk + $shooting;
    }

    public function submit()
    {
        $data = $this->form->getState();

        if ($this->jumlah_tiket === 0) {
            Notification::make()
                ->title('Gagal')
                ->body('Minimal pilih 1 tiket!')
                ->danger()
                ->send();
            return;
        }

        DB::beginTransaction();
        try {
            $paymentMethod = $data['payment_method'] ?? 'cash';
            
            $booking = Booking::create([
                'customer_name' => $data['customer_name'] ?? 'Walk-in Guest',
                'customer_phone' => $data['customer_phone'] ?? '0800000000',
                'customer_email' => 'walkin_' . time() . '@example.com',
                'booking_date' => now(),
                'visit_date' => now(),
                'total_price' => $this->harga_total,
                'status' => $paymentMethod === 'midtrans' ? 'pending' : 'paid',
                'payment_method' => $paymentMethod,
            ]);

            $packagesDb = \App\Models\TourPackage::where('is_active', true)->get()->keyBy('name');

            if ((int) $data['qty_group'] > 0) {
                BookingItem::create(['booking_id' => $booking->id, 'category' => 'group', 'quantity' => (int) $data['qty_group'], 'price_per_item' => $packagesDb['Paket Group']->base_price ?? 200000]);
            }
            if ((int) $data['qty_pancar_trek'] > 0) {
                BookingItem::create(['booking_id' => $booking->id, 'category' => 'pancar_trek', 'quantity' => (int) $data['qty_pancar_trek'], 'price_per_item' => $packagesDb['Pancar Trek']->base_price ?? 165000]);
            }
            if ((int) $data['qty_pancar_school'] > 0) {
                BookingItem::create(['booking_id' => $booking->id, 'category' => 'pancar_school', 'quantity' => (int) $data['qty_pancar_school'], 'price_per_item' => $packagesDb['Pancar School']->base_price ?? 125000]);
            }
            if ((int) $data['qty_prewedding'] > 0) {
                BookingItem::create(['booking_id' => $booking->id, 'category' => 'prewedding', 'quantity' => (int) $data['qty_prewedding'], 'price_per_item' => $packagesDb['Prewedding / Wedding Photo']->base_price ?? 750000]);
            }
            if ((int) $data['qty_foto_produk'] > 0) {
                BookingItem::create(['booking_id' => $booking->id, 'category' => 'foto_produk', 'quantity' => (int) $data['qty_foto_produk'], 'price_per_item' => $packagesDb['Foto Produk']->base_price ?? 7500000]);
            }
            if ((int) $data['qty_shooting'] > 0) {
                BookingItem::create(['booking_id' => $booking->id, 'category' => 'shooting', 'quantity' => (int) $data['qty_shooting'], 'price_per_item' => $packagesDb['Shooting Komersial']->base_price ?? 20000000]);
            }

            $ticketService = app(TicketService::class);
            $ticketService->generateTicketsForBooking($booking, []);

            DB::commit();

            if ($paymentMethod === 'midtrans') {
                \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
                \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

                $params = [
                    'transaction_details' => [
                        'order_id' => $booking->uuid,
                        'gross_amount' => (int) $booking->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => $booking->customer_name,
                        'email' => $booking->customer_email,
                        'phone' => $booking->customer_phone,
                    ],
                ];

                try {
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    $booking->update(['snap_token' => $snapToken]);
                    
                    Notification::make()
                        ->title('Mengarahkan ke Pembayaran Midtrans')
                        ->success()
                        ->send();
                        
                    return redirect()->to(url('/invoice/' . $booking->uuid));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Midtrans Error (Offline): ' . $e->getMessage());
                    Notification::make()
                        ->title('Gagal terhubung ke Midtrans')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            }

            Notification::make()
                ->title('Pesanan berhasil dibuat, mengarahkan ke Cetak Tiket')
                ->success()
                ->send();

            return redirect()->to(url('/booking/' . $booking->uuid . '/pos-print'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Terjadi Kesalahan')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
