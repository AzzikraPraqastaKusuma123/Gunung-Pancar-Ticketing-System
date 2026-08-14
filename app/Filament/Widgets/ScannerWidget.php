<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ScannerWidget extends Widget
{

    protected string $view = 'filament.widgets.scanner-widget';
    protected static ?int $sort = 3;
    
    public function getColumnSpan(): int | string | array
    {
        return [
            'default' => 'full',
            'md' => 'full',
            'xl' => 'full',
        ];
    }

    // Poll every 3 seconds to refresh last scanned tickets
    protected static string $pollingInterval = '3s';

    public $recentScans = [];

    public function mount(): void
    {
        $this->loadRecentScans();
    }

    public function loadRecentScans(): void
    {
        $this->recentScans = Ticket::with('booking', 'scannedBy')
            ->where('status', 'used')
            ->whereNotNull('used_at')
            ->orderByDesc('used_at')
            ->limit(8)
            ->get()
            ->map(fn ($ticket) => [
                'ticket_number'     => $ticket->ticket_number,
                'customer_name'     => $ticket->booking?->customer_name ?? '-',
                'category'          => $ticket->category ?? '-',
                'participant_count' => $ticket->participant_count ?? 1,
                'participant_name'  => $ticket->participant_name ?? '-',
                'scanned_by'        => $ticket->scannedBy?->name ?? 'System',
                'used_at'           => $ticket->used_at?->format('H:i:s'),
                'used_at_human'     => $ticket->used_at?->diffForHumans(),
                'is_latest'         => false,
            ])
            ->toArray();

        // Mark the first one as latest
        if (!empty($this->recentScans)) {
            $this->recentScans[0]['is_latest'] = true;
        }
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['super_admin', 'ticketing']);
    }
}
