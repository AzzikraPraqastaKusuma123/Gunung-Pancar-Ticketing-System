<?php

namespace App\Filament\Resources\ShiftLogs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ShiftLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
                \Filament\Forms\Components\Select::make('shift_type')
                    ->label('Tipe Shift')
                    ->options([
                        'morning' => 'Pagi (Morning)',
                        'afternoon' => 'Siang (Afternoon)',
                        'night' => 'Malam (Night)',
                    ])
                    ->required()
                    ->default('morning'),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'ongoing' => 'Sedang Berjalan (Ongoing)',
                        'completed' => 'Selesai (Completed)',
                    ])
                    ->required()
                    ->default('ongoing'),
                Textarea::make('notes')
                    ->label('Catatan Jaga')
                    ->columnSpanFull(),
            ]);
    }
}
