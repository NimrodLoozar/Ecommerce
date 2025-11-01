<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaseAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'car_id',
        'user_id',
        'lease_duration',
        'monthly_payment',
        'down_payment',
        'annual_mileage_limit',
        'excess_mileage_charge',
        'start_date',
        'end_date',
        'status',
        'contract_file',
    ];

    protected function casts(): array
    {
        return [
            'lease_duration' => 'integer',
            'monthly_payment' => 'decimal:2',
            'down_payment' => 'decimal:2',
            'annual_mileage_limit' => 'integer',
            'excess_mileage_charge' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Get the order for this lease agreement.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the car for this lease agreement.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Get the user for this lease agreement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only active leases.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only pending leases.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if lease is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Calculate total lease cost.
     */
    public function totalCost(): float
    {
        return ($this->monthly_payment * $this->lease_duration) + $this->down_payment;
    }

    /**
     * Activate the lease.
     */
    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Terminate the lease.
     */
    public function terminate(): void
    {
        $this->update(['status' => 'terminated']);
    }
}
