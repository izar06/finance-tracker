<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'amount',
        'category',
        'payment_method',
        'date',
        'notes',
        'is_recurring',
        'recurring_frequency',
        'recurring_ends_at',
        'next_recurring_date',
        'recurring_parent_id',
    ];

    protected $casts = [
        'date'                => 'date',
        'amount'              => 'decimal:2',
        'is_recurring'        => 'boolean',
        'recurring_ends_at'   => 'date',
        'next_recurring_date' => 'date',
    ];

    public static array $incomeCategories = [
        'Gaji', 'Freelance', 'Bisnis', 'Investasi', 'Bonus', 'Hadiah', 'Lainnya',
    ];

    public static array $expenseCategories = [
        'Makanan & Minuman', 'Transportasi', 'Belanja', 'Kesehatan',
        'Pendidikan', 'Hiburan', 'Tagihan & Utilitas', 'Cicilan', 'Tabungan', 'Lainnya',
    ];

    public static array $paymentMethods = [
        'Tunai'          => '💵',
        'Transfer Bank'  => '🏦',
        'Kartu Debit'    => '💳',
        'Kartu Kredit'   => '💳',
        'E-Wallet'       => '📱',
        'QRIS'           => '📲',
        'Paylater/BNPL'  => '🛒',
        'Cek/Giro'       => '📝',
        'Lainnya'        => '🔖',
    ];

    public static array $recurringFrequencies = [
        'daily'   => 'Setiap Hari',
        'weekly'  => 'Setiap Minggu',
        'monthly' => 'Setiap Bulan',
        'yearly'  => 'Setiap Tahun',
    ];

    // ── Global scope: selalu filter by user yang login ──────────────────────
    protected static function booted(): void
    {
        static::addGlobalScope('user', function ($q) {
            if (Auth::check()) {
                $q->where('transactions.user_id', Auth::id());
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recurringParent()
    {
        return $this->belongsTo(Transaction::class, 'recurring_parent_id');
    }

    public function recurringChildren()
    {
        return $this->hasMany(Transaction::class, 'recurring_parent_id');
    }

    public function scopeIncome($query)    { return $query->where('type', 'income'); }
    public function scopeExpense($query)   { return $query->where('type', 'expense'); }
    public function scopeThisMonth($query) { return $query->whereYear('date', now()->year)->whereMonth('date', now()->month); }
    public function scopeThisYear($query)  { return $query->whereYear('date', now()->year); }
    public function scopeRecurring($query) { return $query->where('is_recurring', true); }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format((float) ($this->amount ?? 0), 0, ',', '.');
    }

    public function getRecurringFrequencyLabelAttribute(): string
    {
        return self::$recurringFrequencies[$this->recurring_frequency] ?? '';
    }

    /** Hitung next_recurring_date berdasarkan tanggal saat ini */
    public function computeNextDate(): ?Carbon
    {
        $base = $this->next_recurring_date ?? Carbon::parse($this->date);

        $next = match ($this->recurring_frequency) {
            'daily'   => $base->copy()->addDay(),
            'weekly'  => $base->copy()->addWeek(),
            'monthly' => $base->copy()->addMonth(),
            'yearly'  => $base->copy()->addYear(),
            default   => null,
        };

        if (!$next) return null;

        // Jika sudah melewati recurring_ends_at, transaksi selesai
        if ($this->recurring_ends_at && $next->gt($this->recurring_ends_at)) {
            return null;
        }

        return $next;
    }
}
