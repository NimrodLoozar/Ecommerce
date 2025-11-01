<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured cars and categories.
     */
    public function index(): View
    {
        // Cache featured cars for 1 hour
        $featuredCars = Cache::remember('home.featured_cars', 3600, function () {
            return Car::with(['brand', 'carModel', 'category', 'condition', 'images'])
                ->available()
                ->featured()
                ->latest()
                ->take(8)
                ->get();
        });

        // Cache brands with images for 1 day (for category cards display)
        $brands = Cache::remember('home.brands', 86400, function () {
            return Brand::whereNotNull('image')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });

        // Cache categories for 1 day
        $categories = Cache::remember('home.categories', 86400, function () {
            return Category::withCount('cars')
                ->orderBy('name')
                ->get();
        });

        // Get latest cars (not cached to show real-time updates)
        $latestCars = Car::with(['brand', 'carModel', 'category', 'images'])
            ->available()
            ->latest()
            ->take(4)
            ->get();

        // Get statistics
        $stats = [
            'total_cars' => Car::available()->count(),
            'total_brands' => Brand::count(),
            'total_categories' => Category::count(),
            'cars_sold_this_month' => Car::sold()
                ->whereMonth('updated_at', now()->month)
                ->count(),
        ];

        return view('welcome', compact('featuredCars', 'brands', 'categories', 'latestCars', 'stats'));
    }
}
