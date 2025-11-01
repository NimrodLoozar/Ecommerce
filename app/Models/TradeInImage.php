<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeInImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'trade_in_id',
        'image_path',
        'image_type',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the trade-in that owns this image.
     */
    public function tradeIn(): BelongsTo
    {
        return $this->belongsTo(TradeIn::class);
    }

    /**
     * Scope a query to filter by image type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('image_type', $type);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if image is an exterior photo.
     */
    public function isExterior(): bool
    {
        return $this->image_type === 'exterior';
    }

    /**
     * Check if image is an interior photo.
     */
    public function isInterior(): bool
    {
        return $this->image_type === 'interior';
    }

    /**
     * Check if image shows damage.
     */
    public function isDamage(): bool
    {
        return $this->image_type === 'damage';
    }

    /**
     * Check if image is a document.
     */
    public function isDocument(): bool
    {
        return $this->image_type === 'documents';
    }
}
