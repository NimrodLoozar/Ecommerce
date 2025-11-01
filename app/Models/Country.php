<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'currency_id',
        'tax_rate',
        'phone_code',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'tax_rate' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the default currency for this country.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get all tax rates for this country.
     */
    public function taxRates(): HasMany
    {
        return $this->hasMany(TaxRate::class);
    }

    /**
     * Get all addresses in this country.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Scope a query to only include active countries.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Check if this country is active for delivery.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the formatted phone code.
     */
    public function formattedPhoneCode(): string
    {
        return '+' . ltrim($this->phone_code, '+');
    }

    /**
     * Get the tax rate for this country (or specific state if provided).
     */
    public function getTaxRate(?string $state = null): float
    {
        if ($state) {
            $taxRate = $this->taxRates()
                ->where('state', $state)
                ->where('is_active', true)
                ->first();

            if ($taxRate) {
                return $taxRate->rate;
            }
        }

        // Return country default tax rate
        return $this->tax_rate;
    }
}
