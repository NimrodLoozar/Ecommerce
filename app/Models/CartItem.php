<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'car_id',
        'quantity',
        'purchase_type',
        'lease_duration',
        'price_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'lease_duration' => 'integer',
            'price_snapshot' => 'decimal:2',
        ];
    }

    /**
     * Get the cart that owns this item.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the car for this cart item.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Get the subtotal for this item.
     */
    public function subtotal(): float
    {
        return $this->price_snapshot * $this->quantity;
    }

    /**
     * Check if this is a lease purchase.
     */
    public function isLease(): bool
    {
        return $this->purchase_type === 'lease';
    }
}
