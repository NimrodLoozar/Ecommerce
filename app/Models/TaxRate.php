<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'state',
        'rate',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the country this tax rate belongs to.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Scope a query to only include active tax rates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by country.
     */
    public function scopeForCountry($query, int $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    /**
     * Scope a query to filter by state.
     */
    public function scopeForState($query, string $state)
    {
        return $query->where('state', $state);
    }

    /**
     * Check if this tax rate is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Calculate tax amount for a given subtotal.
     */
    public function calculateTax(float $subtotal): float
    {
        return round($subtotal * ($this->rate / 100), 2);
    }

    /**
     * Get formatted rate as percentage.
     */
    public function formattedRate(): string
    {
        return number_format($this->rate, 2) . '%';
    }

    /**
     * Find applicable tax rate for a country and optional state.
     */
    public static function findApplicable(int $countryId, ?string $state = null): ?self
    {
        $query = static::active()->forCountry($countryId);

        if ($state) {
            $specific = $query->clone()->forState($state)->first();
            if ($specific) {
                return $specific;
            }
        }

        // Return country-level rate if no state-specific rate found
        return $query->whereNull('state')->first();
    }
}
