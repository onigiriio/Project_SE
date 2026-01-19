<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'borrow_id',
        'amount',
        'reason',
        'status',
        'amount_paid',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the user who has the fine.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrow record associated with this fine.
     */
    public function borrow(): BelongsTo
    {
        return $this->belongsTo(Borrow::class);
    }

    /**
     * Get the remaining balance for this fine.
     */
    public function getRemainingBalance(): float
    {
        return (float) ($this->amount - $this->amount_paid);
    }

    /**
     * Mark fine as paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Record a payment towards this fine.
     */
    public function recordPayment(float $amount): void
    {
        $newAmountPaid = $this->amount_paid + $amount;
        
        $this->update([
            'amount_paid' => $newAmountPaid,
            'status' => $newAmountPaid >= $this->amount ? 'paid' : 'partial',
            'paid_at' => $newAmountPaid >= $this->amount ? now() : null,
        ]);
    }
}
