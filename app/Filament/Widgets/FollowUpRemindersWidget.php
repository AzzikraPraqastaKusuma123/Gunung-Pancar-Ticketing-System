<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Lead;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\Action;

use Illuminate\Support\Facades\Auth;

class FollowUpRemindersWidget extends TableWidget
{

    protected static ?int $sort = 2;
    public function getColumnSpan(): int | string | array
    {
        return [
            'default' => 'full',
            'md' => 'full',
            'xl' => 'full',
        ];
    }
    
    protected static ?string $heading = 'Pengingat Follow-up';

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['super_admin', 'sales']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Lead::query()
                    ->whereNotNull('next_follow_up')
                    ->whereDate('next_follow_up', '<=', now())
                    ->whereNotIn('status', ['Booked', 'Completed', 'Cancelled'])
            )
            ->columns([
                TextColumn::make('name')->label('Nama Pelanggan')->searchable(),
                TextColumn::make('phone')->label('No. Telepon'),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => fn ($state) => in_array($state, ['New Lead', 'Contacted']),
                        'primary' => fn ($state) => in_array($state, ['Qualified', 'Quotation', 'Negotiation']),
                    ]),
                TextColumn::make('pic_sales')->label('Sales'),
                TextColumn::make('next_follow_up')->label('Jadwal Follow-up')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view_lead')
                    ->label('Lihat Prospek')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Lead $record): string => \App\Filament\Resources\Leads\LeadResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
