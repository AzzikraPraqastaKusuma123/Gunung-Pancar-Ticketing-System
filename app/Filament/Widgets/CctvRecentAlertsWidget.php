<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CctvRecentAlertsWidget extends Widget
{
    protected string $view = 'filament.widgets.cctv-recent-alerts-widget';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('security');
    }

    public function getAlerts(): array
    {
        return [
            [
                'time' => now()->subMinutes(2)->format('H:i:s'),
                'camera' => 'CAM-04 (Gate Utama)',
                'message' => 'Kendaraan mendekat (Plat B 1234 XYZ)',
                'level' => 'info', // info, warning, danger
            ],
            [
                'time' => now()->subMinutes(15)->format('H:i:s'),
                'camera' => 'CAM-12 (Area Parkir Motor)',
                'message' => 'Pergerakan mencurigakan terdeteksi',
                'level' => 'warning',
            ],
            [
                'time' => now()->subMinutes(45)->format('H:i:s'),
                'camera' => 'CAM-08 (Pintu Belakang)',
                'message' => 'Koneksi jaringan terputus (RTO)',
                'level' => 'danger',
            ],
            [
                'time' => now()->subHours(1)->format('H:i:s'),
                'camera' => 'Sistem Utama DVR',
                'message' => 'Backup harian selesai dilakukan (Sukses)',
                'level' => 'success',
            ],
        ];
    }
}
