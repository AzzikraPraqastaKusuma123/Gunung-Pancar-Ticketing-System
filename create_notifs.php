<?php
use App\Models\User;
use Filament\Notifications\Notification;

$users = User::all();
foreach($users as $user) {
    Notification::make()
        ->title('🚨 CCTV Offline: Gerbang Utama')
        ->body('Koneksi terputus ke CAM-01 sejak 10 menit lalu. Harap periksa jaringan.')
        ->danger()
        ->sendToDatabase($user);
        
    Notification::make()
        ->title('Motion Terdeteksi di Zona A')
        ->body('Sistem mendeteksi pergerakan tidak wajar pada area Glamping A.')
        ->warning()
        ->sendToDatabase($user);
        
    Notification::make()
        ->title('Sistem Normal')
        ->body('Maintenance rutin berhasil diselesaikan. Semua perangkat aktif.')
        ->success()
        ->sendToDatabase($user);
}
echo "Notifications created successfully.";
