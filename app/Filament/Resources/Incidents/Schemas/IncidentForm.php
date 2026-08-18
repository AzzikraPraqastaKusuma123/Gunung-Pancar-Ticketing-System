<?php

namespace App\Filament\Resources\Incidents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class IncidentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Insiden')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('location')
                    ->label('Lokasi (Opsional)')
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('severity')
                    ->label('Tingkat Keparahan')
                    ->options([
                        'low' => 'Rendah (Low)',
                        'medium' => 'Sedang (Medium)',
                        'high' => 'Tinggi (High)',
                        'critical' => 'Kritis (Critical)',
                    ])
                    ->required()
                    ->default('low'),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Status Laporan')
                    ->options([
                        'open' => 'Terbuka (Open)',
                        'investigating' => 'Sedang Diselidiki (Investigating)',
                        'resolved' => 'Diselesaikan (Resolved)',
                        'closed' => 'Ditutup (Closed)',
                    ])
                    ->required()
                    ->default('open'),
                \Filament\Forms\Components\Hidden::make('reported_by')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
