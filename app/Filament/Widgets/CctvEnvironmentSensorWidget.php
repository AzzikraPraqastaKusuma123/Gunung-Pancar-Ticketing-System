<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CctvEnvironmentSensorWidget extends Widget
{
    protected string $view = 'filament.widgets.cctv-environment-sensor-widget';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('security');
    }

    public function getEnvironmentData(): array
    {
        return [
            'temperature' => '24.5',
            'humidity' => '78',
            'wind_speed' => '12',
            'weather_status' => 'Cerah Berawan',
            'alert_level' => 'normal', // normal, warning, danger
        ];
    }
}
