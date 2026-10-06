<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'business_type',
        'logo_path',
        'phone',
        'address',
        'low_stock_threshold',
        'receipt_footer',
        'currency',
        'subscription_tier',
        'subscription_expires_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'low_stock_threshold' => 'integer',
            'subscription_expires_at' => 'datetime',
        ];
    }

    public function isPro(): bool
    {
        return ($this->subscription_tier ?? 'pro') === 'pro';
    }

    public function isStarter(): bool
    {
        return ($this->subscription_tier ?? '') === 'starter';
    }

    public function isFree(): bool
    {
        return ($this->subscription_tier ?? '') === 'free';
    }

    public function planName(): string
    {
        return match ($this->subscription_tier) {
            'starter' => 'Starter',
            'free' => 'Free',
            default => 'Pro',
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(CashierShift::class);
    }

    public function activeShift(): ?CashierShift
    {
        return $this->shifts()->where('status', 'open')->latest('opened_at')->first();
    }
}
