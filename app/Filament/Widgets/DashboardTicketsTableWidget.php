<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\Tickets\Tables\TicketsTable;

class DashboardTicketsTableWidget extends BaseWidget
{
    protected static ?int $sort = 9;
    
    public function getColumnSpan(): int | string | array
    {
        return [
            'default' => 'full',
            'md' => 'full',
            'xl' => 'full',
        ];
    }

    public static function canView(): bool
    {
        return ! auth()->user()->hasRole('sales');
    }

    public function table(Table $table): Table
    {
        return TicketsTable::configure(
            $table->query(Ticket::query()->latest())
        )->heading('Riwayat Tiket Terakhir')
         ->striped()
         ->defaultPaginationPageOption(5)
         ->poll('15s');
    }
}
