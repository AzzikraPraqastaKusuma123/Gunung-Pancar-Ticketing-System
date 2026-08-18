<?php

namespace App\Filament\Widgets;

use App\Models\Device;
use Filament\Widgets\Widget;

class CctvLiveFeedsWidget extends Widget
{
    protected string $view = 'filament.widgets.cctv-live-feeds-widget';
    protected static ?int $sort = 2; // Right after Status Widget
    protected int | string | array $columnSpan = 'full'; // Span all columns

    public static function canView(): bool
    {
        return auth()->user()->hasRole('security');
    }

    public function getDevices()
    {
        // Get all active cctv cameras, limit to 8 for the dashboard collage to prevent clutter
        return Device::where('type', 'cctv')
            ->where('status', 'active')
            ->take(8)
            ->get();
    }
}
