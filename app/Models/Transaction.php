<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'customer_id',
        'shift_id',
        'invoice_number',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'payment_status',
        'payment_reference',
        'qris_payload',
        'status',
        'notes',
        'customer_name',
        'customer_phone',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(CashierShift::class, 'shift_id');
    }

    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('transactions.created_at', today());
    }

    /**
     * Mark transaction as paid and atomically decrement product stocks.
     */
    public function markAsPaid(): bool
    {
        if ($this->payment_status === 'paid' && $this->status === 'completed') {
            return false;
        }

        return DB::transaction(function () {
            $this->update([
                'payment_status' => 'paid',
                'status' => 'completed',
            ]);

            foreach ($this->items as $item) {
                if ($item->product_id) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if ($product) {
                        $product->decrement('stock', $item->quantity);
                    }
                }
            }

            if ($this->customer_id) {
                Customer::where('id', $this->customer_id)->increment('total_spent', $this->total_amount);
            }

            return true;
        });
    }
}
