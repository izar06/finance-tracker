<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'name', 'icon', 'is_default'];

    protected $casts = ['is_default' => 'boolean'];

    // Kategori default yang di-seed saat user pertama kali login
    public static array $defaultIncome = [
        ['name' => 'Gaji',       'icon' => '💼'],
        ['name' => 'Freelance',  'icon' => '💻'],
        ['name' => 'Bisnis',     'icon' => '🏢'],
        ['name' => 'Investasi',  'icon' => '📈'],
        ['name' => 'Bonus',      'icon' => '🎁'],
        ['name' => 'Hadiah',     'icon' => '🎀'],
        ['name' => 'Lainnya',    'icon' => '💰'],
    ];

    public static array $defaultExpense = [
        ['name' => 'Makanan & Minuman', 'icon' => '🍽️'],
        ['name' => 'Transportasi',      'icon' => '🚗'],
        ['name' => 'Belanja',           'icon' => '🛍️'],
        ['name' => 'Kesehatan',         'icon' => '🏥'],
        ['name' => 'Pendidikan',        'icon' => '📚'],
        ['name' => 'Hiburan',           'icon' => '🎮'],
        ['name' => 'Tagihan & Utilitas','icon' => '⚡'],
        ['name' => 'Cicilan',           'icon' => '🏦'],
        ['name' => 'Tabungan',          'icon' => '🐷'],
        ['name' => 'Lainnya',           'icon' => '📦'],
    ];

    // Global scope: filter by user
    protected static function booted(): void
    {
        static::addGlobalScope('user', function ($q) {
            if (Auth::check()) {
                $q->where('categories.user_id', Auth::id());
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

    public function scopeIncome($q)  { return $q->where('type', 'income'); }
    public function scopeExpense($q) { return $q->where('type', 'expense'); }

    // Seed default categories untuk user baru
    public static function seedForUser(int $userId): void
    {
        $existing = self::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->count();

        if ($existing > 0) return;

        $rows = [];
        foreach (self::$defaultIncome as $cat) {
            $rows[] = ['user_id' => $userId, 'type' => 'income', 'name' => $cat['name'], 'icon' => $cat['icon'], 'is_default' => true, 'created_at' => now(), 'updated_at' => now()];
        }
        foreach (self::$defaultExpense as $cat) {
            $rows[] = ['user_id' => $userId, 'type' => 'expense', 'name' => $cat['name'], 'icon' => $cat['icon'], 'is_default' => true, 'created_at' => now(), 'updated_at' => now()];
        }

        self::withoutGlobalScopes()->insert($rows);
    }

    // Helper: ambil nama kategori as plain array (untuk select dropdown)
    public static function namesForType(string $type): array
    {
        return self::where('type', $type)->orderBy('name')->pluck('name')->toArray();
    }
}
