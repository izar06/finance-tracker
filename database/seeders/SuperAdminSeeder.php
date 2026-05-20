<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Opsi 1: Jadikan user pertama (terlama) sebagai superadmin
        $firstUser = User::orderBy('id')->first();
        if ($firstUser) {
            $firstUser->update(['role' => 'superadmin', 'status' => 'active']);
            $this->command->info("User \"{$firstUser->name}\" ({$firstUser->email}) dijadikan superadmin.");
        }

        // Opsi 2: Buat akun superadmin baru jika belum ada user sama sekali
        if (!$firstUser) {
            $admin = User::create([
                'name'     => 'Super Admin',
                'email'    => 'admin@financetracker.com',
                'password' => Hash::make('superadmin123'),
                'role'     => 'superadmin',
                'status'   => 'active',
            ]);
            $this->command->info("Akun superadmin baru dibuat: {$admin->email} / superadmin123");
        }

        $this->command->info('Selesai. Pastikan ganti password default jika menggunakan akun baru.');
    }
}
