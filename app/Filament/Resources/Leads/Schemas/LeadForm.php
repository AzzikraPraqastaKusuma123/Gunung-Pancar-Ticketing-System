<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Info Pelanggan')
                            ->schema([
                                TextInput::make('name')->label('Nama Pelanggan')->required()->maxLength(255),
                                TextInput::make('phone')->label('No. Telepon')->tel()->required()->maxLength(255),
                                Select::make('customer_segment')->label('Segmen Pelanggan')
                                    ->options([
                                        'family' => 'Keluarga',
                                        'friends' => 'Teman/Komunitas',
                                        'corporate' => 'Perusahaan',
                                        'school' => 'Sekolah',
                                        'outbound' => 'Outbound',
                                    ]),
                            ])->columnSpan(1),
                        
                        Section::make('Detail Aktivitas')
                            ->schema([
                                Select::make('activity_type')->label('Tipe Aktivitas')
                                    ->options([
                                        'camping' => 'Camping',
                                        'outbound' => 'Outbound',
                                        'trekking' => 'Trekking',
                                        'gathering' => 'Gathering',
                                    ]),
                                TextInput::make('pax')->label('Jumlah Peserta (Pax)')->numeric(),
                                DatePicker::make('event_date')->label('Tanggal Acara'),
                                Textarea::make('needs')->label('Kebutuhan Khusus')->columnSpanFull(),
                            ])->columnSpan(1),
                        
                        Section::make('Status & Pipeline')
                            ->schema([
                                Select::make('source')->label('Sumber Prospek')
                                    ->options([
                                        'whatsapp' => 'WhatsApp',
                                        'instagram' => 'Instagram',
                                        'facebook' => 'Facebook',
                                        'website' => 'Website',
                                    ]),
                                TextInput::make('pic_sales')->label('PIC / Sales'),
                                Select::make('status')->label('Status')
                                    ->options([
                                        'New Lead' => 'Prospek Baru',
                                        'Contacted' => 'Sudah Dihubungi',
                                        'Qualified' => 'Kualifikasi Masuk',
                                        'Quotation' => 'Kirim Penawaran',
                                        'Negotiation' => 'Negosiasi',
                                        'Booked' => 'Deal / Booked',
                                        'Completed' => 'Selesai',
                                    ])->default('New Lead')->required(),
                                DateTimePicker::make('last_follow_up')->label('Terakhir Follow-up'),
                                DateTimePicker::make('next_follow_up')->label('Jadwal Follow-up Berikutnya'),
                                Textarea::make('notes')->label('Catatan')->columnSpanFull(),
                            ])->columnSpan(1),
                    ])
            ]);
    }
}
