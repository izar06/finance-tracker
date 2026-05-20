<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function ($user) {
            PaymentMethod::seedForUser($user->id);
        });

        $this->command->info('Metode pembayaran default berhasil di-seed untuk semua user.');
    }
}
