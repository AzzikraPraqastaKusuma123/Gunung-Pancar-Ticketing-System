<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TourPackage;

class TourPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Tiket Dewasa',
                'description' => 'Tiket masuk reguler untuk orang dewasa.',
                'base_price' => 50000,
            ],
            [
                'name' => 'Tiket Anak',
                'description' => 'Tiket masuk untuk anak-anak.',
                'base_price' => 25000,
            ],
            [
                'name' => 'Paket Group',
                'description' => 'Berlibur bersama rombongan lebih seru dan hemat.',
                'base_price' => 200000,
            ],
            [
                'name' => 'Pancar Trek',
                'description' => 'Nikmati perjalanan setengah hari dengan fasilitas lengkap yang mendukung eksplorasi alam Gunung Pancar dari awal hingga akhir!',
                'base_price' => 165000,
            ],
            [
                'name' => 'Pancar School',
                'description' => 'Kegiatan jadi lebih tertata dengan fasilitas utama yang siap mendukung aktivitas dari awal hingga akhir.',
                'base_price' => 125000,
            ],
            [
                'name' => 'Prewedding / Wedding Photo',
                'description' => 'Hadirkan nuansa romantis di setiap frame dengan backdrop hutan pinus yang alami dan elegan.',
                'base_price' => 750000,
            ],
            [
                'name' => 'Foto Produk',
                'description' => 'Foto produk dengan sentuhan alam yang estetik untuk hasil visual yang lebih menarik, profesional, dan siap digunakan di berbagai media promosi.',
                'base_price' => 7500000,
            ],
            [
                'name' => 'Shooting Komersial',
                'description' => 'Lokasi outdoor sinematik dengan karakter hutan pinus yang kuat, cocok untuk produksi visual dengan berbagai mood dan konsep.',
                'base_price' => 20000000,
            ],
        ];

        foreach ($packages as $pkg) {
            TourPackage::updateOrCreate(['name' => $pkg['name']], $pkg);
        }
    }
}
