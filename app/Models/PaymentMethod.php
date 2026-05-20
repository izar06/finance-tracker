<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'icon', 'is_default'];

    protected $casts = ['is_default' => 'boolean'];

    // Default payment methods yang di-seed saat user register/login
    public static array $defaults = [
        ['name' => 'Tunai',         'icon' => '💵'],
        ['name' => 'Kartu Debit',   'icon' => '💳'],
        ['name' => 'Kartu Kredit',  'icon' => '💎'],
        ['name' => 'Transfer Bank', 'icon' => '🏦'],
        ['name' => 'E-Wallet',      'icon' => '📱'],
        ['name' => 'QRIS',          'icon' => '📷'],
        ['name' => 'Cek/Giro',      'icon' => '📝'],
        ['name' => 'Lainnya',       'icon' => '🔖'],
    ];

    // Global scope: filter by user
    protected static function booted(): void
    {
        static::addGlobalScope('user', function ($q) {
            if (Auth::check()) {
                $q->where('payment_methods.user_id', Auth::id());
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

    // Seed default payment methods untuk user baru
    public static function seedForUser(int $userId): void
    {
        $existing = self::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->count();

        if ($existing > 0) return;

        $rows = [];
        foreach (self::$defaults as $pm) {
            $rows[] = [
                'user_id'    => $userId,
                'name'       => $pm['name'],
                'icon'       => $pm['icon'],
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        self::withoutGlobalScopes()->insert($rows);
    }

    // Helper: ambil sebagai collection terurut
    public static function ordered()
    {
        return self::orderBy('is_default', 'desc')->orderBy('name')->get();
    }
}
