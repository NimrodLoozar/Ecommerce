<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DealerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'business_registration',
        'tax_id',
        'logo',
        'description',
        'phone',
        'website',
        'commission_rate',
        'subscription_plan',
        'status',
        'approved_by',
        'approved_at',
        'bank_account',
        'documents',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'approved_at' => 'datetime',
            'documents' => 'array',
        ];
    }

    /**
     * Get the user associated with this dealer profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved this dealer.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all analytics records for this dealer.
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(DealerAnalytics::class, 'dealer_id');
    }

    /**
     * Get all cars for this dealer.
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'dealer_id');
    }

    /**
     * Get all commissions for this dealer.
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class, 'dealer_id');
    }

    /**
     * Scope a query to only include pending dealers.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved dealers.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include suspended dealers.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Check if dealer is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if dealer is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if dealer is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Approve the dealer profile.
     */
    public function approve(int $approverId): bool
    {
        return $this->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Suspend the dealer profile.
     */
    public function suspend(): bool
    {
        return $this->update(['status' => 'suspended']);
    }

    /**
     * Reject the dealer profile.
     */
    public function reject(): bool
    {
        return $this->update(['status' => 'rejected']);
    }

    /**
     * Get total pending commissions.
     */
    public function pendingCommissions(): float
    {
        return $this->commissions()->where('status', 'pending')->sum('commission_amount');
    }

    /**
     * Get total paid commissions.
     */
    public function paidCommissions(): float
    {
        return $this->commissions()->where('status', 'paid')->sum('commission_amount');
    }

    /**
     * Get analytics for a specific period.
     */
    public function getAnalytics(string $period): ?DealerAnalytics
    {
        return $this->analytics()->where('period', $period)->first();
    }
}
