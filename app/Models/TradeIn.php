<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradeIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'brand_id',
        'car_model_id',
        'year',
        'mileage',
        'condition',
        'vin',
        'exterior_color',
        'transmission',
        'fuel_type',
        'description',
        'estimated_value',
        'offered_value',
        'final_value',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'mileage' => 'integer',
            'estimated_value' => 'decimal:2',
            'offered_value' => 'decimal:2',
            'final_value' => 'decimal:2',
            'reviewed_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the user who submitted the trade-in.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order where this trade-in was used.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the car model.
     */
    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    /**
     * Get the admin/dealer who reviewed this trade-in.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get all images for this trade-in.
     */
    public function images(): HasMany
    {
        return $this->hasMany(TradeInImage::class)->orderBy('sort_order');
    }

    /**
     * Scope a query to only include pending trade-ins.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include trade-ins under review.
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    /**
     * Scope a query to only include trade-ins with offers.
     */
    public function scopeOfferMade($query)
    {
        return $query->where('status', 'offer_made');
    }

    /**
     * Scope a query to only include accepted trade-ins.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope a query to only include completed trade-ins.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if trade-in is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if trade-in has an offer.
     */
    public function hasOffer(): bool
    {
        return !is_null($this->offered_value);
    }

    /**
     * Check if offer has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if trade-in is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if trade-in has been used in an order.
     */
    public function isUsed(): bool
    {
        return !is_null($this->order_id);
    }

    /**
     * Mark as under review.
     */
    public function markUnderReview(int $reviewerId): bool
    {
        return $this->update([
            'status' => 'under_review',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Make an offer.
     */
    public function makeOffer(float $offeredValue, int $expiryDays = 7): bool
    {
        return $this->update([
            'status' => 'offer_made',
            'offered_value' => $offeredValue,
            'expires_at' => now()->addDays($expiryDays),
        ]);
    }

    /**
     * Accept the offer.
     */
    public function acceptOffer(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        return $this->update([
            'status' => 'accepted',
            'final_value' => $this->offered_value,
        ]);
    }

    /**
     * Reject the trade-in.
     */
    public function reject(): bool
    {
        return $this->update(['status' => 'rejected']);
    }

    /**
     * Mark as completed.
     */
    public function complete(int $orderId): bool
    {
        return $this->update([
            'status' => 'completed',
            'order_id' => $orderId,
        ]);
    }
}
