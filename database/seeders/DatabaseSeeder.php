<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\FinancialGoal;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TransactionSeeder::class,
            FinancialGoalSeeder::class,
            AssetSeeder::class,
        ]);
    }
}
