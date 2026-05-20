<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category');           // kategori pengeluaran (dari Transaction::$expenseCategories)
            $table->decimal('amount', 15, 2);     // batas anggaran
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedSmallInteger('year'); // misal 2026
            $table->text('notes')->nullable();
            $table->timestamps();

            // Satu kategori hanya boleh punya 1 budget per bulan per user
            $table->unique(['user_id', 'category', 'month', 'year'], 'budgets_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
