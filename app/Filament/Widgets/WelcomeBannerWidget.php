<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeBannerWidget extends Widget
{
    protected string $view = 'filament.widgets.welcome-banner';

    protected static ?int $sort = -10;

    protected int | string | array $columnSpan = 'full';
}
