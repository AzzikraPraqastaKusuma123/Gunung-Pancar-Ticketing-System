<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CctvStatusWidget extends Widget
{
    protected static ?int $sort = 1;
    protected string $view = 'filament.widgets.cctv-status-widget';
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('security');
    }
}
