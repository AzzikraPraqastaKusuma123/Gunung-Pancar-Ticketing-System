<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ticket_number')->label('Nomor Tiket')
                    ->placeholder('(Otomatis dibuat oleh sistem)')
                    ->disabled()
                    ->dehydrated(false),
                Select::make('booking_id')->label('Pesanan Terkait')
                    ->relationship('booking', 'customer_name')
                    ->required(),
                Select::make('category')->label('Kategori Tiket')
                    ->options([
                        'dewasa' => 'Dewasa',
                        'anak-anak' => 'Anak-anak',
                        'group' => 'Grup',
                    ])
                    ->required(),
                TextInput::make('participant_count')->label('Jumlah Peserta')
                    ->numeric()
                    ->default(1)
                    ->required(),
                TextInput::make('qr_code_path')->label('Kode QR')
                    ->placeholder('(Otomatis dibuat oleh sistem)')
                    ->disabled(),
                Select::make('status')->label('Status')
                    ->options([
                        'booked' => 'Dipesan',
                        'printed' => 'Dicetak',
                        'used' => 'Digunakan',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->required()
                    ->default('booked'),
                Select::make('scanned_by_user_id')->label('Dipindai Oleh')
                    ->relationship('scannedBy', 'name')
                    ->disabled(),
                DateTimePicker::make('used_at')->label('Digunakan Pada')
                    ->disabled(),
            ]);
    }
}
