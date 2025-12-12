<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'stripe_payment_method_id',
        'last_four',
        'brand',
        'exp_month',
        'exp_year',
        'cardholder_name',
        'is_default',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'exp_month' => 'integer',
            'exp_year' => 'integer',
        ];
    }

    /**
     * Get the user that owns the payment method.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the card is expired.
     */
    public function isExpired(): bool
    {
        if (!$this->exp_month || !$this->exp_year) {
            return false;
        }

        $now = now();
        $expiry = now()->setYear($this->exp_year)->setMonth($this->exp_month)->endOfMonth();

        return $now->isAfter($expiry);
    }

    /**
     * Get a formatted display string for the payment method.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->type === 'card') {
            $brand = ucfirst($this->brand ?? 'Card');
            return "{$brand} ending in {$this->last_four}";
        }

        return ucfirst($this->type);
    }
}
