<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'car_id',
    ];

    /**
     * Get the user who owns the wishlist item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the car in the wishlist.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Scope a query to only include wishlist items for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if a car is in the user's wishlist.
     */
    public static function isInWishlist(int $userId, int $carId): bool
    {
        return static::where('user_id', $userId)
            ->where('car_id', $carId)
            ->exists();
    }

    /**
     * Toggle a car in the user's wishlist.
     */
    public static function toggle(int $userId, int $carId): bool
    {
        $wishlist = static::where('user_id', $userId)
            ->where('car_id', $carId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return false; // Removed
        }

        static::create([
            'user_id' => $userId,
            'car_id' => $carId,
        ]);

        return true; // Added
    }
}
