<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Display advanced search results with comprehensive filtering.
     */
    public function index(Request $request): View
    {
        $query = Car::with(['brand', 'carModel', 'category', 'condition', 'images'])
            ->available();

        // Text search (search in brand name, model name, description)
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('brand', function ($brandQuery) use ($searchTerm) {
                    $brandQuery->where('name', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('carModel', function ($modelQuery) use ($searchTerm) {
                        $modelQuery->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('vin', 'like', "%{$searchTerm}%");
            });
        }

        // Brand filter
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Condition filter
        if ($request->filled('condition_id')) {
            $query->where('condition_id', $request->condition_id);
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Year range
        if ($request->filled('min_year')) {
            $query->where('year', '>=', $request->min_year);
        }

        if ($request->filled('max_year')) {
            $query->where('year', '<=', $request->max_year);
        }

        // Mileage range
        if ($request->filled('min_mileage')) {
            $query->where('mileage', '>=', $request->min_mileage);
        }

        if ($request->filled('max_mileage')) {
            $query->where('mileage', '<=', $request->max_mileage);
        }

        // Fuel type
        if ($request->filled('fuel_type')) {
            if (is_array($request->fuel_type)) {
                $query->whereIn('fuel_type', $request->fuel_type);
            } else {
                $query->where('fuel_type', $request->fuel_type);
            }
        }

        // Transmission
        if ($request->filled('transmission')) {
            if (is_array($request->transmission)) {
                $query->whereIn('transmission', $request->transmission);
            } else {
                $query->where('transmission', $request->transmission);
            }
        }

        // Body type
        if ($request->filled('body_type')) {
            if (is_array($request->body_type)) {
                $query->whereIn('body_type', $request->body_type);
            } else {
                $query->where('body_type', $request->body_type);
            }
        }

        // Color
        if ($request->filled('exterior_color')) {
            $query->where('exterior_color', $request->exterior_color);
        }

        // Features (cars that have ALL selected features)
        if ($request->filled('features')) {
            $featureIds = is_array($request->features) ? $request->features : [$request->features];
            foreach ($featureIds as $featureId) {
                $query->whereHas('features', function ($q) use ($featureId) {
                    $q->where('features.id', $featureId);
                });
            }
        }

        // Number of seats
        if ($request->filled('min_seats')) {
            $query->where('seats', '>=', $request->min_seats);
        }

        if ($request->filled('max_seats')) {
            $query->where('seats', '<=', $request->max_seats);
        }

        // Engine size
        if ($request->filled('min_engine_size')) {
            $query->where('engine_size', '>=', $request->min_engine_size);
        }

        if ($request->filled('max_engine_size')) {
            $query->where('engine_size', '<=', $request->max_engine_size);
        }

        // Power (horsepower)
        if ($request->filled('min_power')) {
            $query->where('power', '>=', $request->min_power);
        }

        if ($request->filled('max_power')) {
            $query->where('power', '<=', $request->max_power);
        }

        // Featured only
        if ($request->boolean('featured_only')) {
            $query->featured();
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['price', 'year', 'mileage', 'created_at', 'views'];
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

        // Get available filter values (for dynamic filtering)
        $availableFuelTypes = Car::available()
            ->distinct()
            ->pluck('fuel_type')
            ->filter()
            ->sort()
            ->values();

        $availableTransmissions = Car::available()
            ->distinct()
            ->pluck('transmission')
            ->filter()
            ->sort()
            ->values();

        $availableBodyTypes = Car::available()
            ->distinct()
            ->pluck('body_type')
            ->filter()
            ->sort()
            ->values();

        $availableColors = Car::available()
            ->distinct()
            ->pluck('exterior_color')
            ->filter()
            ->sort()
            ->values();

        return view('search.index', compact(
            'cars',
            'brands',
            'categories',
            'conditions',
            'features',
            'availableFuelTypes',
            'availableTransmissions',
            'availableBodyTypes',
            'availableColors'
        ));
    }
}
