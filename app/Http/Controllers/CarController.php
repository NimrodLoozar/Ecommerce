<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarController extends Controller
{
    /**
     * Display a listing of cars with filters and pagination.
     */
    public function index(Request $request): View
    {
        $query = Car::with(['brand', 'carModel', 'category', 'condition', 'images'])
            ->available();

        // Apply filters
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('condition_id')) {
            $query->where('condition_id', $request->condition_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('min_year')) {
            $query->where('year', '>=', $request->min_year);
        }

        if ($request->filled('max_year')) {
            $query->where('year', '<=', $request->max_year);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->filled('min_mileage')) {
            $query->where('mileage', '>=', $request->min_mileage);
        }

        if ($request->filled('max_mileage')) {
            $query->where('mileage', '<=', $request->max_mileage);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['price', 'year', 'mileage', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Paginate results
        $cars = $query->paginate(12)->withQueryString();

        // Get filter options
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $conditions = Condition::orderBy('name')->get();
        $features = Feature::orderBy('name')->get();

        return view('cars.index', compact('cars', 'brands', 'categories', 'conditions', 'features'));
    }

    /**
     * Display the specified car.
     */
    public function show(Car $car): View
    {
        // Eager load relationships
        $car->load([
            'brand',
            'carModel',
            'category',
            'condition',
            'images',
            'features',
            'reviews' => function ($query) {
                $query->approved()->latest()->take(5);
            },
            'reviews.user',
        ]);

        // Increment view count
        $car->increment('views_count');

        // Get similar cars (same brand or category)
        $similarCars = Car::with(['brand', 'carModel', 'images'])
            ->available()
            ->where('id', '!=', $car->id)
            ->where(function ($query) use ($car) {
                $query->where('brand_id', $car->brand_id)
                    ->orWhere('category_id', $car->category_id);
            })
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Calculate average rating
        $averageRating = $car->reviews()->approved()->avg('rating');

        return view('cars.show', compact('car', 'similarCars', 'averageRating'));
    }
}
