<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');                        // Nama tagihan (Listrik, Netflix, dll)
            $table->string('category');                    // Kategori (Utilitas, Langganan, dll)
            $table->decimal('amount', 15, 2);              // Nominal tagihan
            $table->unsignedTinyInteger('due_day');        // Tanggal jatuh tempo (1-31)
            $table->enum('frequency', [                    // Frekuensi tagihan
                'monthly', 'quarterly', 'yearly'
            ])->default('monthly');
            $table->enum('status', [                       // Status tagihan
                'active', 'paused', 'cancelled'
            ])->default('active');
            $table->string('payment_method')->nullable();  // Metode bayar default
            $table->string('icon')->default('📄');
            $table->text('notes')->nullable();
            $table->date('last_paid_at')->nullable();      // Terakhir dibayar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
