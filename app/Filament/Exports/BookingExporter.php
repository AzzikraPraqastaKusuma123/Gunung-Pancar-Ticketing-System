<?php

namespace App\Filament\Exports;

use App\Models\Booking;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Str;

class BookingExporter extends Exporter
{
    protected static ?string $model = Booking::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID Pesanan'),
            ExportColumn::make('uuid')
                ->label('Kode Tiket / UUID'),
            ExportColumn::make('lead.name')
                ->label('Asal / Lead'),
            ExportColumn::make('customer_name')
                ->label('Nama Pelanggan'),
            ExportColumn::make('customer_email')
                ->label('Email'),
            ExportColumn::make('customer_phone')
                ->label('No. WhatsApp'),
            ExportColumn::make('customer_segment')
                ->label('Segmen Pelanggan'),
            ExportColumn::make('activity_type')
                ->label('Jenis Aktivitas'),
            ExportColumn::make('pic_sales')
                ->label('PIC Sales'),
            ExportColumn::make('booking_date')
                ->label('Tanggal Booking'),
            ExportColumn::make('visit_date')
                ->label('Tanggal Kunjungan'),
            ExportColumn::make('total_price')
                ->label('Total Harga (Rp)')
                ->state(fn ($record) => number_format($record->total_price, 0, ',', '.')),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('special_requirements')
                ->label('Permintaan Khusus'),
            ExportColumn::make('payment_method')
                ->label('Metode Pembayaran'),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your booking export has completed and ' . Str::of('row')->counted($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Str::of('row')->counted($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
