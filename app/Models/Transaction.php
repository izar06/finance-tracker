<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
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
        'Cek/Giro'       => '📝',
        'Lainnya'        => '🔖',
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

    public function scopeIncome($query)    { return $query->where('type', 'income'); }
    public function scopeExpense($query)   { return $query->where('type', 'expense'); }
    public function scopeThisMonth($query) { return $query->whereYear('date', now()->year)->whereMonth('date', now()->month); }
    public function scopeThisYear($query)  { return $query->whereYear('date', now()->year); }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
