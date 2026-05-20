<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'amount',
        'month',
        'year',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'month'  => 'integer',
        'year'   => 'integer',
    ];

    // ── Global scope: selalu filter by user yang login ──────────────────
    protected static function booted(): void
    {
        static::addGlobalScope('user', function ($q) {
            if (Auth::check()) {
                $q->where('budgets.user_id', Auth::id());
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

    // ── Accessor: total pengeluaran aktual untuk bulan/kategori ini ──────
    public function getSpentAttribute(): float
    {
        return (float) Transaction::expense()
            ->where('category', $this->category)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->sum('amount');
    }

    public function getRemainingAttribute(): float
    {
        return max(0, (float) $this->amount - $this->spent);
    }

    public function getPercentageAttribute(): float
    {
        if ((float) $this->amount <= 0) return 0;
        return min(100, round(($this->spent / (float) $this->amount) * 100, 1));
    }

    public function getIsOverBudgetAttribute(): bool
    {
        return $this->spent > (float) $this->amount;
    }

    public function getOverspentAttribute(): float
    {
        return max(0, $this->spent - (float) $this->amount);
    }

    // Status warna berdasarkan persentase pemakaian
    public function getStatusColorAttribute(): string
    {
        $pct = $this->percentage;
        if ($pct >= 100) return 'rose';
        if ($pct >= 80)  return 'amber';
        return 'emerald';
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
