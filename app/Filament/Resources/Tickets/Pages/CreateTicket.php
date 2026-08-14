<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Generate nomor tiket
        $ticketNumber = 'TIX-' . strtoupper(\Illuminate\Support\Str::random(8));
        $data['ticket_number'] = $ticketNumber;

        // 2. Generate QR Code
        $qrData = route('scanner') . '?ticket=' . $ticketNumber;
        $fileName = 'qrcodes/' . $ticketNumber . '.png';
        $filePath = storage_path('app/public/' . $fileName);
        
        if (!file_exists(storage_path('app/public/qrcodes'))) {
            mkdir(storage_path('app/public/qrcodes'), 0755, true);
        }

        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($qrData, $filePath);

        $data['qr_code_path'] = $fileName;

        return $data;
    }
}
