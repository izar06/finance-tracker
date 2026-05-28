<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessRecurringTransactions extends Command
{
    protected $signature   = 'transactions:process-recurring
                              {--dry-run : Tampilkan transaksi yang akan dibuat tanpa benar-benar menyimpan}';

    protected $description = 'Buat transaksi otomatis dari template transaksi berulang yang sudah jatuh tempo';

    public function handle(): int
    {
        $today    = Carbon::today();
        $isDryRun = $this->option('dry-run');

        $this->info($isDryRun
            ? "🔍 DRY RUN — tidak ada data yang disimpan"
            : "⚙️  Memproses transaksi berulang untuk tanggal {$today->format('d M Y')}..."
        );

        $recurringTransactions = Transaction::withoutGlobalScopes()
            ->where('is_recurring', true)
            ->whereNotNull('next_recurring_date')
            ->where('next_recurring_date', '<=', $today)
            ->get();

        if ($recurringTransactions->isEmpty()) {
            $this->info('✅ Tidak ada transaksi berulang yang perlu diproses.');
            return self::SUCCESS;
        }

        $created = 0;
        $skipped = 0;

        foreach ($recurringTransactions as $parent) {
            $alreadyExists = Transaction::withoutGlobalScopes()
                ->where('user_id', $parent->user_id)
                ->where('recurring_parent_id', $parent->id)
                ->where('date', $parent->next_recurring_date->format('Y-m-d'))
                ->exists();

            if ($alreadyExists) {
                $this->line("  ⏭  Skip (duplikat): [{$parent->user_id}] {$parent->title} — {$parent->next_recurring_date->format('d M Y')}");
                $skipped++;
                $nextDate = $parent->computeNextDate();
                if (!$isDryRun) {
                    $parent->update(['next_recurring_date' => $nextDate]);
                }
                continue;
            }

            $transactionDate = $parent->next_recurring_date->copy();
            $nextDate        = $parent->computeNextDate();

            $this->line("  ✚  Buat: [{$parent->user_id}] {$parent->title} — {$transactionDate->format('d M Y')}");

            if (!$isDryRun) {
                Transaction::withoutGlobalScopes()->create([
                    'user_id'             => $parent->user_id,
                    'type'                => $parent->type,
                    'title'               => $parent->title,
                    'amount'              => $parent->amount,
                    'category'            => $parent->category,
                    'payment_method'      => $parent->payment_method,
                    'date'                => $transactionDate,
                    'notes'               => $parent->notes,
                    'is_recurring'        => false,
                    'recurring_parent_id' => $parent->id,
                    'recurring_frequency' => null,
                    'recurring_ends_at'   => null,
                    'next_recurring_date' => null,
                ]);

                $parent->update(['next_recurring_date' => $nextDate]);
            }

            $created++;
        }

        $this->newLine();
        $this->info("📊 Selesai: {$created} transaksi dibuat, {$skipped} dilewati.");

        return self::SUCCESS;
    }
}
