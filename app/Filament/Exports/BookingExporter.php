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
                ->label('ID'),
            ExportColumn::make('uuid')
                ->label('UUID'),
            ExportColumn::make('lead.name'),
            ExportColumn::make('customer_name'),
            ExportColumn::make('customer_email'),
            ExportColumn::make('customer_phone'),
            ExportColumn::make('customer_segment'),
            ExportColumn::make('activity_type'),
            ExportColumn::make('pic_sales'),
            ExportColumn::make('booking_date'),
            ExportColumn::make('visit_date'),
            ExportColumn::make('total_price'),
            ExportColumn::make('status'),
            ExportColumn::make('special_requirements'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('payment_url'),
            ExportColumn::make('payment_method'),
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
