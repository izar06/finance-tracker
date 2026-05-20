<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Generate 12 months of data
        for ($m = 0; $m < 12; $m++) {
            $date = $now->copy()->subMonths($m);

            // Monthly salary
            Transaction::create([
                'type'     => 'income',
                'title'    => 'Gaji Bulanan',
                'amount'   => 8_500_000,
                'category' => 'Gaji',
                'date'     => $date->copy()->startOfMonth()->addDays(4),
                'notes'    => 'Transfer dari perusahaan',
            ]);

            // Freelance income (some months)
            if ($m % 3 === 0) {
                Transaction::create([
                    'type'     => 'income',
                    'title'    => 'Proyek Freelance Web',
                    'amount'   => rand(1_500_000, 3_500_000),
                    'category' => 'Freelance',
                    'date'     => $date->copy()->startOfMonth()->addDays(rand(5, 20)),
                ]);
            }

            // Regular expenses
            $expenses = [
                ['Makan Siang Kantor', 'Makanan & Minuman', rand(50_000, 80_000) * 22],
                ['Bensin Motor', 'Transportasi', rand(150_000, 250_000)],
                ['Listrik & Air', 'Tagihan & Utilitas', rand(250_000, 350_000)],
                ['Internet Rumah', 'Tagihan & Utilitas', 299_000],
                ['Belanja Bulanan Indomaret', 'Belanja', rand(300_000, 600_000)],
                ['Langganan Netflix', 'Hiburan', 54_000],
                ['Cicilan Motor', 'Cicilan', 750_000],
                ['Tabungan Darurat', 'Tabungan', 500_000],
            ];

            foreach ($expenses as $idx => [$title, $category, $amount]) {
                Transaction::create([
                    'type'     => 'expense',
                    'title'    => $title,
                    'amount'   => $amount,
                    'category' => $category,
                    'date'     => $date->copy()->startOfMonth()->addDays($idx + 1),
                ]);
            }

            // Random extra expenses
            $extras = [
                ['Makan di Restoran', 'Makanan & Minuman', rand(80_000, 200_000)],
                ['Grab/Gojek', 'Transportasi', rand(50_000, 150_000)],
                ['Baju & Celana', 'Belanja', rand(150_000, 400_000)],
                ['Obat-obatan', 'Kesehatan', rand(50_000, 200_000)],
            ];

            foreach ($extras as [$title, $category, $amount]) {
                if (rand(0, 1)) {
                    Transaction::create([
                        'type'     => 'expense',
                        'title'    => $title,
                        'amount'   => $amount,
                        'category' => $category,
                        'date'     => $date->copy()->startOfMonth()->addDays(rand(1, 28)),
                    ]);
                }
            }
        }
    }
}
