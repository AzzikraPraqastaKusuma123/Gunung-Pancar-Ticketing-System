<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CctvDeviceHealthWidget extends Widget
{
    protected string $view = 'filament.widgets.cctv-device-health-widget';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('security');
    }

    public function getDevicesHealth(): array
    {
        return [
            [
                'name' => 'CAM-01 (Gerbang Utama)',
                'status' => 'online',
                'uptime' => '32 Hari 5 Jam',
                'latency' => '12ms',
                'temperature' => '38°C',
            ],
            [
                'name' => 'CAM-02 (Area Parkir)',
                'status' => 'online',
                'uptime' => '15 Hari 12 Jam',
                'latency' => '24ms',
                'temperature' => '42°C',
            ],
            [
                'name' => 'CAM-08 (Pintu Belakang)',
                'status' => 'offline',
                'uptime' => '-',
                'latency' => 'RTO',
                'temperature' => '-',
            ],
            [
                'name' => 'NVR Server Master',
                'status' => 'online',
                'uptime' => '124 Hari 8 Jam',
                'latency' => '2ms',
                'temperature' => '55°C', // Warning
            ],
        ];
    }
}
