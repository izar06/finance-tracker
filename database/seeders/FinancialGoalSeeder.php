<?php

namespace Database\Seeders;

use App\Models\FinancialGoal;
use Illuminate\Database\Seeder;

class FinancialGoalSeeder extends Seeder
{
    public function run(): void
    {
        $goals = [
            [
                'name'           => 'Dana Darurat 6 Bulan',
                'target_amount'  => 51_000_000,
                'current_amount' => 32_500_000,
                'deadline'       => now()->addMonths(8),
                'description'    => 'Mengumpulkan dana darurat setara 6 bulan pengeluaran',
                'status'         => 'active',
                'icon'           => '🛡️',
            ],
            [
                'name'           => 'Liburan ke Jepang',
                'target_amount'  => 25_000_000,
                'current_amount' => 8_750_000,
                'deadline'       => now()->addMonths(14),
                'description'    => 'Liburan 10 hari ke Tokyo dan Osaka',
                'status'         => 'active',
                'icon'           => '✈️',
            ],
            [
                'name'           => 'DP Rumah',
                'target_amount'  => 150_000_000,
                'current_amount' => 45_000_000,
                'deadline'       => now()->addMonths(36),
                'description'    => 'Down payment rumah pertama di pinggiran kota',
                'status'         => 'active',
                'icon'           => '🏠',
            ],
            [
                'name'           => 'Laptop Baru',
                'target_amount'  => 20_000_000,
                'current_amount' => 20_000_000,
                'deadline'       => now()->subMonth(),
                'description'    => 'Ganti laptop untuk bekerja',
                'status'         => 'completed',
                'icon'           => '💻',
            ],
        ];

        foreach ($goals as $goal) {
            FinancialGoal::create($goal);
        }
    }
}
