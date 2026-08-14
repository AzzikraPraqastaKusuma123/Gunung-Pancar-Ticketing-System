<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('lead_id')->label('Prospek Terkait')
                    ->relationship('lead', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('uuid')
                    ->label('UUID')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('customer_name')->label('Nama Pelanggan')
                    ->required(),
                TextInput::make('customer_email')->label('Email Pelanggan')
                    ->email(),
                TextInput::make('customer_phone')->label('No. Telepon')
                    ->tel()
                    ->required(),
                \Filament\Forms\Components\Select::make('customer_segment')->label('Segmen Pelanggan')
                    ->options([
                        'family' => 'Keluarga',
                        'friends' => 'Teman/Komunitas',
                        'corporate' => 'Perusahaan',
                        'school' => 'Sekolah',
                        'outbound' => 'Outbound',
                    ]),
                \Filament\Forms\Components\Select::make('activity_type')->label('Tipe Aktivitas')
                    ->options([
                        'camping' => 'Camping',
                        'outbound' => 'Outbound',
                        'trekking' => 'Trekking',
                        'gathering' => 'Gathering',
                    ]),
                TextInput::make('pic_sales')->label('PIC / Sales'),
                DatePicker::make('booking_date')->label('Tanggal Pesan')
                    ->required(),
                DatePicker::make('visit_date')->label('Tanggal Kunjungan')
                    ->required(),
                \Filament\Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        \Filament\Forms\Components\Select::make('tour_package_id')
                            ->label('Paket Wisata')
                            ->relationship('tourPackage', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('price_per_item', \App\Models\TourPackage::find($state)?->base_price ?? 0)),
                        \Filament\Forms\Components\Select::make('category')->label('Kategori (Opsional)')
                            ->options([
                                'dewasa' => 'Dewasa',
                                'anak-anak' => 'Anak-Anak',
                                'group' => 'Group',
                            ])
                            ->default('dewasa'),
                        TextInput::make('quantity')->label('Jumlah (Pax)')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live(),
                        TextInput::make('price_per_item')->label('Harga per Item')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->live(),
                        \Filament\Forms\Components\Placeholder::make('subtotal')
                            ->label('Subtotal')
                            ->content(function ($get) {
                                return 'Rp ' . number_format((intval($get('quantity')) * floatval($get('price_per_item'))), 0, ',', '.');
                            })
                    ])
                    ->columns(5)
                    ->columnSpanFull(),
                TextInput::make('total_price')->label('Total Harga Keseluruhan (Simpan Manual)')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')->label('Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Lunas',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->required()
                    ->default('pending'),
                \Filament\Forms\Components\Textarea::make('special_requirements')->label('Kebutuhan Khusus')->columnSpanFull(),
            ]);
    }
}
