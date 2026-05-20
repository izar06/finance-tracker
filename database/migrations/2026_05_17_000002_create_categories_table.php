<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['income', 'expense']);
            $table->string('name');
            $table->string('icon')->default('🏷️');
            $table->boolean('is_default')->default(false); // kategori bawaan sistem
            $table->timestamps();

            $table->unique(['user_id', 'type', 'name'], 'categories_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
