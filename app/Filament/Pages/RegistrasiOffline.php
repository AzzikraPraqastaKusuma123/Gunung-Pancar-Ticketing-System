<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\TourPackage;
use App\Services\TicketService;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

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

    /**
     * Cache paket harga dari DB agar tidak query berkali-kali
     */
    private ?array $packagePriceCache = null;

    /**
     * Ambil harga paket dari DB (selalu dari DB, bukan hardcoded).
     * Jika paket tidak ada atau tidak aktif, nilainya null.
     */
    private function getPackagePrices(): array
    {
        if ($this->packagePriceCache !== null) {
            return $this->packagePriceCache;
        }

        $packages = TourPackage::where('is_active', true)->get()->keyBy('name');

        $this->packagePriceCache = [
            'dewasa'        => optional($packages['Tiket Dewasa'])->base_price,
            'anak'          => optional($packages['Tiket Anak'])->base_price,
            'group'         => optional($packages['Paket Group'])->base_price,
            'pancar_trek'   => optional($packages['Pancar Trek'])->base_price,
            'pancar_school' => optional($packages['Pancar School'])->base_price,
            'prewedding'    => optional($packages['Prewedding / Wedding Photo'])->base_price,
            'foto_produk'   => optional($packages['Foto Produk'])->base_price,
            'shooting'      => optional($packages['Shooting Komersial'])->base_price,
        ];

        return $this->packagePriceCache;
    }

    public function mount(): void
    {
        $this->form->fill([
            'customer_name'     => 'Walk-in Guest',
            'qty_dewasa'        => 0,
            'qty_anak'          => 0,
            'qty_group'         => 0,
            'qty_pancar_trek'   => 0,
            'qty_pancar_school' => 0,
            'qty_prewedding'    => 0,
            'qty_foto_produk'   => 0,
            'qty_shooting'      => 0,
            'payment_method'    => 'cash',
        ]);
    }

    public function form(Schema $form): Schema
    {
        $prices = $this->getPackagePrices();

        // Helper format harga untuk label
        $fmt = fn(?int $price) => $price ? 'Rp ' . number_format($price, 0, ',', '.') : 'Harga N/A';

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

                // ✅ FIX: Tambah tiket Dewasa dan Anak yang sebelumnya tidak ada
                Section::make('Tiket Kunjungan (Individual)')
                    ->schema([
                        TextInput::make('qty_dewasa')
                            ->label("Tiket Dewasa ({$fmt($prices['dewasa'])})")
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_anak')
                            ->label("Tiket Anak ({$fmt($prices['anak'])})")
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                    ])->columns(2),

                Section::make('Paket & Kegiatan')
                    ->schema([
                        TextInput::make('qty_group')
                            ->label("Group 5 Orang ({$fmt($prices['group'])})")
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_pancar_trek')
                            ->label("Pancar Trek ({$fmt($prices['pancar_trek'])})")
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_pancar_school')
                            ->label("Pancar School ({$fmt($prices['pancar_school'])})")
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_prewedding')
                            ->label("Prewedding ({$fmt($prices['prewedding'])})")
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_foto_produk')
                            ->label("Foto Produk ({$fmt($prices['foto_produk'])})")
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(),
                        TextInput::make('qty_shooting')
                            ->label("Shooting Komersial ({$fmt($prices['shooting'])})")
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
                                'cash'     => 'Tunai (Cash)',
                                'midtrans' => 'Midtrans (QRIS / Transfer Bank)',
                            ])
                            ->inline()
                            ->required()
                            ->default('cash'),
                    ]),
            ])
            ->statePath('data');
    }

    // ✅ FIX: Hitung total harga selalu dari DB, tidak ada angka hardcoded
    public function getHargaTotalProperty(): int
    {
        $prices = $this->getPackagePrices();

        $keys = ['dewasa', 'anak', 'group', 'pancar_trek', 'pancar_school', 'prewedding', 'foto_produk', 'shooting'];
        $total = 0;
        foreach ($keys as $key) {
            $qty = (int) ($this->data['qty_' . $key] ?? 0);
            if ($qty > 0 && isset($prices[$key])) {
                $total += $qty * $prices[$key];
            }
        }
        return $total;
    }

    public function getJumlahTiketProperty(): int
    {
        return (int) ($this->data['qty_dewasa']        ?? 0)
             + (int) ($this->data['qty_anak']          ?? 0)
             + (int) ($this->data['qty_group']         ?? 0)
             + (int) ($this->data['qty_pancar_trek']   ?? 0)
             + (int) ($this->data['qty_pancar_school'] ?? 0)
             + (int) ($this->data['qty_prewedding']    ?? 0)
             + (int) ($this->data['qty_foto_produk']   ?? 0)
             + (int) ($this->data['qty_shooting']      ?? 0);
    }

    public function submit()
    {
        $data   = $this->form->getState();
        $prices = $this->getPackagePrices();

        if ($this->jumlah_tiket === 0) {
            Notification::make()
                ->title('Gagal')
                ->body('Minimal pilih 1 tiket!')
                ->danger()
                ->send();
            return;
        }

        // ✅ FIX: Validasi paket ada di DB sebelum transaksi (bukan fallback hardcoded)
        $itemsMap = [
            'dewasa'        => 'Tiket Dewasa',
            'anak'          => 'Tiket Anak',
            'group'         => 'Paket Group',
            'pancar_trek'   => 'Pancar Trek',
            'pancar_school' => 'Pancar School',
            'prewedding'    => 'Prewedding / Wedding Photo',
            'foto_produk'   => 'Foto Produk',
            'shooting'      => 'Shooting Komersial',
        ];

        $missingPackages = [];
        foreach ($itemsMap as $key => $packageName) {
            $qty = (int) ($data['qty_' . $key] ?? 0);
            if ($qty > 0 && $prices[$key] === null) {
                $missingPackages[] = $packageName;
            }
        }

        if (!empty($missingPackages)) {
            Notification::make()
                ->title('Konfigurasi Paket Tidak Ditemukan')
                ->body('Paket berikut tidak aktif di database: ' . implode(', ', $missingPackages) . '. Silakan tambahkan di menu Tour Packages.')
                ->danger()
                ->send();
            return;
        }

        DB::beginTransaction();
        try {
            $paymentMethod = $data['payment_method'] ?? 'cash';

            $booking = Booking::create([
                'customer_name'  => $data['customer_name']  ?? 'Walk-in Guest',
                'customer_phone' => $data['customer_phone'] ?? '0800000000',
                'customer_email' => 'walkin_' . time() . '@example.com',
                'booking_date'   => now(),
                'visit_date'     => now(),
                'total_price'    => $this->harga_total,
                'status'         => $paymentMethod === 'midtrans' ? 'pending' : 'paid',
                'payment_method' => $paymentMethod,
            ]);

            // Buat BookingItems untuk semua kategori yang dipilih (harga dari DB)
            foreach ($itemsMap as $key => $packageName) {
                $qty = (int) ($data['qty_' . $key] ?? 0);
                if ($qty > 0) {
                    BookingItem::create([
                        'booking_id'     => $booking->id,
                        'category'       => $key,
                        'quantity'       => $qty,
                        'price_per_item' => $prices[$key],
                    ]);
                }
            }

            $ticketService = app(TicketService::class);
            $ticketService->generateTicketsForBooking($booking, []);

            DB::commit();

            if ($paymentMethod === 'midtrans') {
                \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
                \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                \Midtrans\Config::$isSanitized  = config('services.midtrans.is_sanitized');
                \Midtrans\Config::$is3ds        = config('services.midtrans.is_3ds');

                $params = [
                    'transaction_details' => [
                        'order_id'     => $booking->uuid,
                        'gross_amount' => (int) $booking->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => $booking->customer_name,
                        'email'      => $booking->customer_email,
                        'phone'      => $booking->customer_phone,
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
