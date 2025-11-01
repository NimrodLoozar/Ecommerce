<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
        'decimals',
    ];

    protected function casts(): array
    {
        return [
            'exchange_rate' => 'decimal:6',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'decimals' => 'integer',
        ];
    }

    /**
     * Get all countries using this currency.
     */
    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }

    /**
     * Scope a query to only include active currencies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to get the default currency.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Check if this is the default currency.
     */
    public function isDefault(): bool
    {
        return $this->is_default;
    }

    /**
     * Check if this currency is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Convert an amount from this currency to another.
     */
    public function convertTo(Currency $targetCurrency, float $amount): float
    {
        // Convert to base currency first, then to target currency
        $baseAmount = $amount / $this->exchange_rate;
        return round($baseAmount * $targetCurrency->exchange_rate, $targetCurrency->decimals);
    }

    /**
     * Format an amount with this currency's symbol.
     */
    public function format(float $amount): string
    {
        return $this->symbol . number_format($amount, $this->decimals);
    }
}
