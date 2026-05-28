<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class TransactionsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $imported = 0;
    public int $skipped  = 0;
    public array $errors = [];

    private array $validTypes      = ['income', 'expense', 'pemasukan', 'pengeluaran'];
    private array $validCategories = [];

    public function __construct()
    {
        $this->validCategories = array_merge(
            Category::namesForType('income'),
            Category::namesForType('expense')
        );
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 karena baris 1 = heading

            try {
                // ── Normalise type ──────────────────────────────────────
                $rawType = strtolower(trim($row['tipe'] ?? $row['type'] ?? ''));
                $type = match($rawType) {
                    'income', 'pemasukan' => 'income',
                    'expense', 'pengeluaran' => 'expense',
                    default => null,
                };

                if (!$type) {
                    $this->errors[] = "Baris {$rowNum}: Tipe '{$rawType}' tidak valid (harus 'income'/'expense' atau 'pemasukan'/'pengeluaran').";
                    $this->skipped++;
                    continue;
                }

                // ── Title ───────────────────────────────────────────────
                $title = trim($row['judul'] ?? $row['title'] ?? '');
                if (!$title) {
                    $this->errors[] = "Baris {$rowNum}: Kolom Judul tidak boleh kosong.";
                    $this->skipped++;
                    continue;
                }

                // ── Amount ──────────────────────────────────────────────
                $rawAmount = str_replace(['.', ',', ' ', 'Rp', 'rp'], ['', '.', '', '', ''], trim((string)($row['jumlah'] ?? $row['amount'] ?? $row['jumlah_rp'] ?? 0)));
                $amount = (float) $rawAmount;
                if ($amount <= 0) {
                    $this->errors[] = "Baris {$rowNum}: Jumlah harus lebih dari 0.";
                    $this->skipped++;
                    continue;
                }

                // ── Category ────────────────────────────────────────────
                $category = trim($row['kategori'] ?? $row['category'] ?? '');
                if (!$category) {
                    $category = 'Lainnya';
                }

                // ── Date ────────────────────────────────────────────────
                $rawDate = trim($row['tanggal'] ?? $row['date'] ?? '');
                try {
                    if (is_numeric($rawDate)) {
                        // Excel serial date
                        $date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawDate));
                    } else {
                        $date = Carbon::parse($rawDate);
                    }
                } catch (\Exception $e) {
                    $this->errors[] = "Baris {$rowNum}: Format tanggal '{$rawDate}' tidak dikenali.";
                    $this->skipped++;
                    continue;
                }

                // ── Optional fields ─────────────────────────────────────
                $paymentMethod = trim($row['metode_pembayaran'] ?? $row['metode'] ?? $row['payment_method'] ?? '') ?: null;
                $notes         = trim($row['catatan'] ?? $row['notes'] ?? '') ?: null;

                Transaction::create([
                    'user_id'        => Auth::id(),
                    'type'           => $type,
                    'title'          => $title,
                    'amount'         => $amount,
                    'category'       => $category,
                    'payment_method' => $paymentMethod,
                    'date'           => $date->format('Y-m-d'),
                    'notes'          => $notes,
                    'is_recurring'   => false,
                ]);

                $this->imported++;

            } catch (\Exception $e) {
                $this->errors[] = "Baris {$rowNum}: " . $e->getMessage();
                $this->skipped++;
            }
        }
    }
}