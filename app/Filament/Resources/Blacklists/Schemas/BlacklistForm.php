<?php

namespace App\Filament\Resources\Blacklists\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BlacklistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama (Opsional)')
                    ->maxLength(255),
                TextInput::make('vehicle_plate')
                    ->label('Plat Kendaraan')
                    ->maxLength(255),
                Textarea::make('reason')
                    ->label('Alasan/Keterangan')
                    ->required()
                    ->columnSpanFull(),
                \Filament\Forms\Components\FileUpload::make('photo_url')
                    ->label('Foto Bukti')
                    ->image()
                    ->directory('blacklists'),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif (Active)',
                        'resolved' => 'Diselesaikan (Resolved)',
                    ])
                    ->required()
                    ->default('active'),
                \Filament\Forms\Components\Hidden::make('reported_by')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
