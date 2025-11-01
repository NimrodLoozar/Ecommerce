<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'countries',
        'delivery_fee',
        'free_delivery_threshold',
        'estimated_days_min',
        'estimated_days_max',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'countries' => 'array',
            'delivery_fee' => 'decimal:2',
            'free_delivery_threshold' => 'decimal:2',
            'estimated_days_min' => 'integer',
            'estimated_days_max' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include active delivery zones.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if this delivery zone is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if a country is in this delivery zone.
     */
    public function hasCountry(int $countryId): bool
    {
        return in_array($countryId, $this->countries ?? []);
    }

    /**
     * Get delivery fee for an order amount.
     * Returns 0 if order amount exceeds free delivery threshold.
     */
    public function getDeliveryFee(float $orderAmount): float
    {
        if ($this->free_delivery_threshold && $orderAmount >= $this->free_delivery_threshold) {
            return 0;
        }

        return $this->delivery_fee;
    }

    /**
     * Get estimated delivery time range as a string.
     */
    public function estimatedDelivery(): string
    {
        if ($this->estimated_days_min === $this->estimated_days_max) {
            return "{$this->estimated_days_min} " . str('day')->plural($this->estimated_days_min);
        }

        return "{$this->estimated_days_min}-{$this->estimated_days_max} days";
    }

    /**
     * Check if order qualifies for free delivery.
     */
    public function qualifiesForFreeDelivery(float $orderAmount): bool
    {
        return $this->free_delivery_threshold && $orderAmount >= $this->free_delivery_threshold;
    }

    /**
     * Find delivery zone by country ID.
     */
    public static function findByCountry(int $countryId): ?self
    {
        return static::active()
            ->get()
            ->first(fn ($zone) => $zone->hasCountry($countryId));
    }
}
