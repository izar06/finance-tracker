<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // ← add this

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────────
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    // ── Stats per user ─────────────────────────────────────────────────────
    public function transactionCount(): int
    {
        return $this->transactions()->withoutGlobalScopes()->count(); // ← reuse relationship
    }

    public function totalAssets(): float
    {
        return (float) $this->assets()->withoutGlobalScopes()->sum('current_value'); // ← reuse relationship
    }
}