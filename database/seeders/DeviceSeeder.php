<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        Device::create([
            'name' => 'CAM-01 Gerbang Utama',
            'type' => 'cctv',
            'ip_address' => '192.168.1.101',
            'status' => 'active',
            'location' => 'Gerbang',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1557053964-937650ddbfce?q=80&w=600&auto=format&fit=crop'
        ]);

        Device::create([
            'name' => 'CAM-02 Area Parkir',
            'type' => 'cctv',
            'ip_address' => '192.168.1.102',
            'status' => 'active',
            'location' => 'Parkiran',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1584443187802-9519398b1ec3?q=80&w=600&auto=format&fit=crop'
        ]);

        Device::create([
            'name' => 'CAM-03 Zona Camping A',
            'type' => 'cctv',
            'ip_address' => '192.168.1.103',
            'status' => 'active',
            'location' => 'Zona A',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1504280390226-e25f77893c5d?q=80&w=600&auto=format&fit=crop'
        ]);

        Device::create([
            'name' => 'CAM-04 Zona Glamping',
            'type' => 'cctv',
            'ip_address' => '192.168.1.104',
            'status' => 'active',
            'location' => 'Glamping',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?q=80&w=600&auto=format&fit=crop'
        ]);

        Device::create([
            'name' => 'CAM-05 Area Api Unggun',
            'type' => 'cctv',
            'ip_address' => '192.168.1.105',
            'status' => 'offline',
            'location' => 'Tengah',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1504280390226-e25f77893c5d?q=80&w=600&auto=format&fit=crop'
        ]);

        Device::create([
            'name' => 'Router Utama Pancar',
            'type' => 'router',
            'ip_address' => '192.168.1.1',
            'status' => 'active',
            'location' => 'Ruang Server'
        ]);
    }
}
