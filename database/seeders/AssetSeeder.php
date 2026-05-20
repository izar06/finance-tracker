<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            [
                'name'           => 'Yamaha NMAX 2022',
                'type'           => 'vehicle',
                'purchase_price' => 32_000_000,
                'current_value'  => 27_500_000,
                'purchase_date'  => now()->subYears(2),
                'description'    => 'Motor utama untuk commuting sehari-hari',
            ],
            [
                'name'           => 'Reksa Dana Saham Mirae',
                'type'           => 'investment',
                'purchase_price' => 20_000_000,
                'current_value'  => 23_800_000,
                'purchase_date'  => now()->subYears(3),
                'description'    => 'Investasi reksa dana saham jangka panjang',
            ],
            [
                'name'           => 'Tabungan BCA',
                'type'           => 'cash',
                'purchase_price' => 10_000_000,
                'current_value'  => 32_500_000,
                'purchase_date'  => now()->subYears(5),
                'description'    => 'Tabungan utama dan dana darurat',
            ],
            [
                'name'           => 'Deposito Mandiri 12 Bulan',
                'type'           => 'cash',
                'purchase_price' => 25_000_000,
                'current_value'  => 26_437_500,
                'purchase_date'  => now()->subMonths(6),
                'description'    => 'Deposito bunga 5.75% per tahun',
            ],
            [
                'name'           => 'MacBook Pro M2 2023',
                'type'           => 'other',
                'purchase_price' => 22_000_000,
                'current_value'  => 19_000_000,
                'purchase_date'  => now()->subMonths(14),
                'description'    => 'Laptop kerja utama',
            ],
            [
                'name'           => 'Saham BBCA',
                'type'           => 'investment',
                'purchase_price' => 15_000_000,
                'current_value'  => 18_250_000,
                'purchase_date'  => now()->subYears(2)->subMonths(3),
                'description'    => '150 lot saham Bank BCA',
            ],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}
