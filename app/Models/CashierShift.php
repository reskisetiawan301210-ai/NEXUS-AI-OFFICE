<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashierShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'opened_at',
        'closed_at',
        'initial_cash',
        'total_sales',
        'cash_sales',
        'qris_sales',
        'expected_cash',
        'actual_cash',
        'difference',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'initial_cash' => 'decimal:2',
            'total_sales' => 'decimal:2',
            'cash_sales' => 'decimal:2',
            'qris_sales' => 'decimal:2',
            'expected_cash' => 'decimal:2',
            'actual_cash' => 'decimal:2',
            'difference' => 'decimal:2',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'shift_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
