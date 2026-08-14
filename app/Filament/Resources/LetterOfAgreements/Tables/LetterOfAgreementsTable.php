<?php

namespace App\Filament\Resources\LetterOfAgreements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use App\Filament\Resources\LetterOfAgreements\LetterOfAgreementResource;

class LetterOfAgreementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn (\App\Models\LetterOfAgreement $record): string => LetterOfAgreementResource::getUrl('edit', ['record' => $record]),
            )
            ->columns([
                TextColumn::make('document_number')->label('Nomor Dokumen')->searchable(),
                TextColumn::make('lead.name')->label('Nama Prospek')->searchable(),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'draft',
                        'primary' => 'sent',
                        'success' => 'signed',
                    ]),
                TextColumn::make('total_amount')->label('Total Biaya')->numeric(),
                TextColumn::make('valid_until')->label('Berlaku Hingga')->date(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('print_loa')
                    ->label('Cetak LOA')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('loa.print', $record))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
