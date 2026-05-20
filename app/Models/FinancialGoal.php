<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'target_amount', 'current_amount',
        'deadline', 'description', 'status', 'icon',
    ];

    protected $casts = [
        'deadline'       => 'date',
        'target_amount'  => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];

    public static array $icons = ['🎯','🏠','🚗','✈️','📚','💍','💻','🏥','💰','🌟'];

    protected static function booted(): void
    {
        static::addGlobalScope('user', function ($q) {
            if (Auth::check()) {
                $q->where('financial_goals.user_id', Auth::id());
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->current_amount / $this->target_amount) * 100, 1));
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function getFormattedTargetAttribute(): string   { return 'Rp ' . number_format($this->target_amount, 0, ',', '.'); }
    public function getFormattedCurrentAttribute(): string  { return 'Rp ' . number_format($this->current_amount, 0, ',', '.'); }

    public function scopeActive($query)    { return $query->where('status', 'active'); }
    public function scopeCompleted($query) { return $query->where('status', 'completed'); }
}
