<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'category', 'amount', 'due_day',
        'frequency', 'status', 'payment_method', 'icon', 'notes', 'last_paid_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'due_day'      => 'integer',
        'last_paid_at' => 'date',
    ];

    public static array $categories = [
        'Utilitas'      => '⚡',
        'Langganan'     => '📱',
        'Asuransi'      => '🛡️',
        'Cicilan'       => '🏦',
        'Sewa'          => '🏠',
        'Internet'      => '🌐',
        'Telepon'       => '📞',
        'Pendidikan'    => '📚',
        'Kesehatan'     => '🏥',
        'Lainnya'       => '📄',
    ];

    public static array $frequencies = [
        'monthly'   => 'Bulanan',
        'quarterly' => '3 Bulanan',
        'yearly'    => 'Tahunan',
    ];

    public static array $icons = [
        '⚡','💧','🌐','📱','📞','🏠','🚗','🛡️',
        '📚','🏥','🎬','🎵','☁️','🏦','💳','📄',
        '🔌','📺','🎮','✈️','🍕','💰','🧾','🔖',
    ];

    // ── Global Scope ────────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::addGlobalScope('user', function ($q) {
            if (Auth::check()) {
                $q->where('bills.user_id', Auth::id());
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

    // ── Computed: tanggal jatuh tempo berikutnya ─────────────────────────
    public function getNextDueDateAttribute(): Carbon
    {
        $today = now()->startOfDay();
        $day   = min($this->due_day, $today->daysInMonth);

        $candidate = $today->copy()->setDay($day);
        if ($candidate->lt($today)) {
            $candidate->addMonth();
            $candidate->setDay(min($this->due_day, $candidate->daysInMonth));
        }

        return $candidate;
    }

    // ── Computed: berapa hari lagi jatuh tempo ──────────────────────────
    public function getDaysUntilDueAttribute(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->next_due_date, false);
    }

    // ── Computed: status urgensi ─────────────────────────────────────────
    public function getUrgencyAttribute(): string
    {
        $days = $this->days_until_due;
        if ($days < 0)  return 'overdue';
        if ($days <= 3) return 'urgent';
        if ($days <= 7) return 'soon';
        return 'normal';
    }

    // ── Computed: warna berdasarkan urgensi ──────────────────────────────
    public function getUrgencyColorAttribute(): string
    {
        return match($this->urgency) {
            'overdue' => 'rose',
            'urgent'  => 'amber',
            'soon'    => 'blue',
            default   => 'slate',
        };
    }

    // ── Computed: sudah dibayar bulan ini? ──────────────────────────────
    public function getIsPaidThisMonthAttribute(): bool
    {
        if (!$this->last_paid_at) return false;
        return $this->last_paid_at->month === now()->month
            && $this->last_paid_at->year  === now()->year;
    }

    // ── Computed: label frekuensi ────────────────────────────────────────
    public function getFrequencyLabelAttribute(): string
    {
        return self::$frequencies[$this->frequency] ?? $this->frequency;
    }

    // ── Computed: estimasi tagihan per tahun ─────────────────────────────
    public function getYearlyAmountAttribute(): float
    {
        return match($this->frequency) {
            'monthly'   => (float) $this->amount * 12,
            'quarterly' => (float) $this->amount * 4,
            'yearly'    => (float) $this->amount,
            default     => (float) $this->amount * 12,
        };
    }

    // ── Scope ────────────────────────────────────────────────────────────
    public function scopeActive($q)  { return $q->where('status', 'active'); }
    public function scopePaused($q)  { return $q->where('status', 'paused'); }
}
