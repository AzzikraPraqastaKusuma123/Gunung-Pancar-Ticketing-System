<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TourPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Pancar Camp',
                'description' => 'Paket Camping seru bersama teman atau keluarga di tengah hutan pinus.',
                'base_price' => 150000,
            ],
            [
                'name' => 'Pancar Day',
                'description' => 'One day trip untuk bersantai menikmati udara segar Gunung Pancar.',
                'base_price' => 50000,
            ],
            [
                'name' => 'Pancar School',
                'description' => 'Paket khusus untuk sekolah, pesantren, dan lembaga pendidikan.',
                'base_price' => 100000,
            ],
            [
                'name' => 'Pancar Studio',
                'description' => 'Sesi foto profesional atau prewedding di area hutan pinus eksotis.',
                'base_price' => 750000,
            ],
            [
                'name' => 'Pancar Trek',
                'description' => 'Petualangan trekking menjelajah alam Gunung Pancar dengan pemandu.',
                'base_price' => 125000,
            ],
        ];

        foreach ($packages as $pkg) {
            \App\Models\TourPackage::create($pkg);
        }
    }
}
