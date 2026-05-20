<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Seed kategori default untuk semua user yang sudah ada
        User::all()->each(function ($user) {
            Category::seedForUser($user->id);
        });

        $this->command->info('Kategori default berhasil di-seed untuk semua user.');
    }
}
