<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CctvQuickActionWidget extends Widget
{
    protected string $view = 'filament.widgets.cctv-quick-action-widget';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('security');
    }
}
