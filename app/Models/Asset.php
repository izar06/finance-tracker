<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'type', 'purchase_price',
        'current_value', 'purchase_date', 'description',
    ];

    protected $casts = [
        'purchase_date'  => 'date',
        'purchase_price' => 'decimal:2',
        'current_value'  => 'decimal:2',
    ];

    public static array $types     = ['property'=>'Properti','vehicle'=>'Kendaraan','investment'=>'Investasi','cash'=>'Tunai/Tabungan','other'=>'Lainnya'];
    public static array $typeIcons = ['property'=>'🏠','vehicle'=>'🚗','investment'=>'📈','cash'=>'💵','other'=>'📦'];

    protected static function booted(): void
    {
        static::addGlobalScope('user', function ($q) {
            if (Auth::check()) {
                $q->where('assets.user_id', Auth::id());
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }

    public function getGainLossAttribute(): float           { return $this->current_value - $this->purchase_price; }
    public function getGainLossPercentageAttribute(): float { return $this->purchase_price > 0 ? round((($this->current_value - $this->purchase_price) / $this->purchase_price) * 100, 2) : 0; }
    public function getTypeNameAttribute(): string          { return self::$types[$this->type] ?? $this->type; }
    public function getTypeIconAttribute(): string          { return self::$typeIcons[$this->type] ?? '📦'; }
    public function getFormattedCurrentValueAttribute(): string   { return 'Rp ' . number_format($this->current_value, 0, ',', '.'); }
    public function getFormattedPurchasePriceAttribute(): string  { return 'Rp ' . number_format($this->purchase_price, 0, ',', '.'); }
}
