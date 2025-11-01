<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'period',
        'total_listings',
        'total_views',
        'total_inquiries',
        'total_sales',
        'total_revenue',
        'commission_owed',
    ];

    protected function casts(): array
    {
        return [
            'total_listings' => 'integer',
            'total_views' => 'integer',
            'total_inquiries' => 'integer',
            'total_sales' => 'integer',
            'total_revenue' => 'decimal:2',
            'commission_owed' => 'decimal:2',
        ];
    }

    /**
     * Get the dealer profile this analytics belongs to.
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(DealerProfile::class, 'dealer_id');
    }

    /**
     * Scope a query to filter by period.
     */
    public function scopeForPeriod($query, string $period)
    {
        return $query->where('period', $period);
    }

    /**
     * Scope a query to filter by year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('period', 'like', $year . '%');
    }

    /**
     * Get average revenue per sale.
     */
    public function averageRevenuePerSale(): float
    {
        if ($this->total_sales === 0) {
            return 0;
        }

        return round($this->total_revenue / $this->total_sales, 2);
    }

    /**
     * Get conversion rate (sales / views).
     */
    public function conversionRate(): float
    {
        if ($this->total_views === 0) {
            return 0;
        }

        return round(($this->total_sales / $this->total_views) * 100, 2);
    }

    /**
     * Get inquiry to sale conversion rate.
     */
    public function inquiryConversionRate(): float
    {
        if ($this->total_inquiries === 0) {
            return 0;
        }

        return round(($this->total_sales / $this->total_inquiries) * 100, 2);
    }

    /**
     * Increment listings count.
     */
    public function incrementListings(int $count = 1): bool
    {
        return $this->increment('total_listings', $count);
    }

    /**
     * Increment views count.
     */
    public function incrementViews(int $count = 1): bool
    {
        return $this->increment('total_views', $count);
    }

    /**
     * Increment inquiries count.
     */
    public function incrementInquiries(int $count = 1): bool
    {
        return $this->increment('total_inquiries', $count);
    }

    /**
     * Record a sale.
     */
    public function recordSale(float $amount, float $commission): bool
    {
        return $this->update([
            'total_sales' => $this->total_sales + 1,
            'total_revenue' => $this->total_revenue + $amount,
            'commission_owed' => $this->commission_owed + $commission,
        ]);
    }

    /**
     * Get or create analytics for a dealer and period.
     */
    public static function getOrCreate(int $dealerId, string $period): self
    {
        return static::firstOrCreate(
            ['dealer_id' => $dealerId, 'period' => $period],
            [
                'total_listings' => 0,
                'total_views' => 0,
                'total_inquiries' => 0,
                'total_sales' => 0,
                'total_revenue' => 0.00,
                'commission_owed' => 0.00,
            ]
        );
    }
}
