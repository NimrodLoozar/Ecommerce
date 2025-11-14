<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'car_model_id',
        'category_id',
        'condition_id',
        'user_id',
        'dealer_id',
        'vin',
        'title',
        'slug',
        'description',
        'year',
        'mileage',
        'price',
        'lease_price_monthly',
        'fuel_type',
        'transmission',
        'engine_size',
        'horsepower',
        'exterior_color',
        'interior_color',
        'doors',
        'seats',
        'stock_quantity',
        'status',
        'is_featured',
        'views_count',
        'meta_title',
        'meta_description',
        'api_data',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'mileage' => 'integer',
            'price' => 'decimal:2',
            'lease_price_monthly' => 'decimal:2',
            'engine_size' => 'decimal:1',
            'horsepower' => 'integer',
            'doors' => 'integer',
            'seats' => 'integer',
            'stock_quantity' => 'integer',
            'is_featured' => 'boolean',
            'views_count' => 'integer',
            'api_data' => 'array',
        ];
    }

    /**
     * Get the brand that owns this car.
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
     * Get the category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the condition.
     */
    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    /**
     * Get the user (dealer/admin) who listed this car.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the dealer profile associated with this car.
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(DealerProfile::class, 'dealer_id');
    }

    /**
     * Get all images for this car.
     */
    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('sort_order');
    }

    /**
     * Get the primary image.
     */
    public function primaryImage(): HasMany
    {
        return $this->hasMany(CarImage::class)->where('is_primary', true);
    }

    /**
     * Get all features for this car.
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class);
    }

    /**
     * Get all reviews for this car.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get approved reviews for this car.
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Get wishlist entries for this car.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get inquiries for this car.
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Get test drive bookings for this car.
     */
    public function testDrives(): HasMany
    {
        return $this->hasMany(TestDrive::class);
    }

    /**
     * Get cart items for this car.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get order items for this car.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get lease agreements for this car.
     */
    public function leaseAgreements(): HasMany
    {
        return $this->hasMany(LeaseAgreement::class);
    }

    /**
     * Scope to get only available cars.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('stock_quantity', '>', 0);
    }

    /**
     * Scope to get only featured cars.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get only sold cars.
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    /**
     * Scope to filter by price range.
     */
    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope to filter by year range.
     */
    public function scopeYearBetween($query, $min, $max)
    {
        return $query->whereBetween('year', [$min, $max]);
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Check if car is available for purchase.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->stock_quantity > 0;
    }

    /**
     * Check if car has lease option.
     */
    public function hasLeaseOption(): bool
    {
        return $this->lease_price_monthly !== null && $this->lease_price_monthly > 0;
    }

    /**
     * Get average rating for this car.
     */
    public function averageRating(): float
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get total number of approved reviews.
     */
    public function reviewsCount(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get images from the filesystem based on brand and title.
     * Returns an array of image paths relative to public directory.
     */
    public function getFilesystemImages(): array
    {
        $images = [];
        
        $brandName = $this->brand->name;
        $modelName = $this->carModel->name;
        $year = $this->year;
        
        // Try different brand folder name variations (for brands with special characters)
        $brandFolderVariations = [
            $brandName,                           // "Lynk & Co"
            str_replace(' ', '', $brandName),     // "Lynk&Co" (remove spaces)
            strtolower(str_replace(' ', '', $brandName)), // "lynk&co" (lowercase, no spaces)
        ];
        
        // Try different subfolder name variations
        $possibleFolders = [
            // Exact match: "Year Brand Model"
            "{$year} {$brandName} {$modelName}",
            // Try with E-Tech suffix for electric Megane
            "{$year} {$brandName} {$modelName} E-Tech",
            // Try alternate spelling (Megan vs Megane)
            "{$year} {$brandName} " . rtrim($modelName, 'e'),
            // Try with Update suffix
            "{$year} {$brandName} {$modelName} Update",
        ];
        
        $folderPath = null;
        $relativePath = null;
        
        // Try each brand folder variation
        foreach ($brandFolderVariations as $brandFolder) {
            // Find the first matching folder
            foreach ($possibleFolders as $folderName) {
                $testPath = public_path("img/{$brandFolder}/{$folderName}");
                if (file_exists($testPath) && is_dir($testPath)) {
                    $folderPath = $testPath;
                    $relativePath = "img/{$brandFolder}/{$folderName}";
                    break 2; // Break out of both loops
                }
            }
        }
        
        // If no folder found, return empty array
        if (!$folderPath) {
            return $images;
        }

        // Get all image files from the directory
        $files = scandir($folderPath);
        
        foreach ($files as $file) {
            // Check if it's an image file (jpg, jpeg, png, webp, avif)
            if (preg_match('/\.(jpg|jpeg|png|webp|avif)$/i', $file)) {
                // Store the relative path from public directory
                $images[] = $relativePath . '/' . $file;
            }
        }
        
        return $images;
    }
}