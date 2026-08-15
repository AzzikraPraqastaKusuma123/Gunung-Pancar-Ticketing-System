<?php

namespace App\Filament\Clusters\CommandCenter\Resources\Devices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->options([
                        'cctv' => 'IP Camera / CCTV',
                        'router' => 'Router',
                        'switch' => 'Switch',
                        'ap' => 'Access Point',
                        'server' => 'Server/NVR'
                    ])
                    ->required(),
                TextInput::make('ip_address')
                    ->ipv4()
                    ->maxLength(255),
                TextInput::make('mac_address')
                    ->maxLength(255),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'offline' => 'Offline',
                        'warning' => 'Warning'
                    ])
                    ->required()
                    ->default('active'),
                TextInput::make('location')
                    ->maxLength(255),
                TextInput::make('stream_url')
                    ->url()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('thumbnail_url')
                    ->url()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }
}
