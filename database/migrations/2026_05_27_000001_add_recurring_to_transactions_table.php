<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('notes');
            $table->enum('recurring_frequency', ['daily', 'weekly', 'monthly', 'yearly'])->nullable()->after('is_recurring');
            $table->date('recurring_ends_at')->nullable()->after('recurring_frequency');
            $table->date('next_recurring_date')->nullable()->after('recurring_ends_at');
            $table->unsignedBigInteger('recurring_parent_id')->nullable()->after('next_recurring_date');

            $table->foreign('recurring_parent_id')
                  ->references('id')
                  ->on('transactions')
                  ->nullOnDelete();

            $table->index(['is_recurring', 'next_recurring_date']);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['recurring_parent_id']);
            $table->dropIndex(['is_recurring', 'next_recurring_date']);
            $table->dropColumn([
                'is_recurring',
                'recurring_frequency',
                'recurring_ends_at',
                'next_recurring_date',
                'recurring_parent_id',
            ]);
        });
    }
};
