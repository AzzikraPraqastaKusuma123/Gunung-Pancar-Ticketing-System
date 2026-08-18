<?php

namespace App\Filament\Resources\Patrols\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class PatrolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('location_name')
                    ->label('Nama Lokasi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('coordinates')
                    ->label('Koordinat (Lat, Long)')
                    ->maxLength(255),
                Textarea::make('notes')
                    ->label('Catatan Patroli')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status Lokasi')
                    ->options([
                        'safe' => 'Aman (Safe)',
                        'suspicious' => 'Mencurigakan (Suspicious)',
                        'incident' => 'Ada Insiden (Incident)',
                    ])
                    ->required()
                    ->default('safe'),
                FileUpload::make('photo_url')
                    ->label('Foto Lokasi / Bukti')
                    ->image()
                    ->directory('patrols'),
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
