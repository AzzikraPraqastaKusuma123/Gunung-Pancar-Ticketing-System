<?php

namespace App\Filament\Resources\LetterOfAgreements\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;

class LetterOfAgreementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('document_number')->label('Nomor Dokumen')
                    ->required()
                    ->default(fn () => 'LOA-' . date('Ymd') . '-' . rand(1000, 9999))
                    ->maxLength(255),
                Select::make('lead_id')->label('Prospek')
                    ->relationship('lead', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('status')->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Terkirim',
                        'signed' => 'Ditandatangani',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->required()
                    ->default('draft'),
                DatePicker::make('valid_until')->label('Berlaku Hingga'),
                TextInput::make('total_amount')->label('Total Biaya')
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('down_payment')->label('Uang Muka (DP)')
                    ->numeric()
                    ->prefix('Rp'),
                Textarea::make('terms_and_conditions')->label('Syarat & Ketentuan')
                    ->columnSpanFull(),
                TextInput::make('signed_by_customer')->label('Ditandatangani Oleh (Pelanggan)')
                    ->maxLength(255),
                TextInput::make('signed_by_company')->label('Ditandatangani Oleh (Perusahaan)')
                    ->maxLength(255),
            ]);
    }
}
