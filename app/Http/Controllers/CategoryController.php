<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories.
     */
    public function index(): View
    {
        $categories = Category::withCount(['cars' => function ($query) {
            $query->available();
        }])
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display cars for a specific category.
     */
    public function show(Request $request, Category $category): View
    {
        $query = $category->cars()
            ->with(['brand', 'carModel', 'condition', 'images'])
            ->available();

        // Apply filters
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
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
        $conditions = Condition::orderBy('name')->get();

        // Get category statistics
        $stats = [
            'total_cars' => $category->cars()->available()->count(),
            'average_price' => $category->cars()->available()->avg('price'),
            'price_range' => [
                'min' => $category->cars()->available()->min('price'),
                'max' => $category->cars()->available()->max('price'),
            ],
        ];

        return view('categories.show', compact('category', 'cars', 'brands', 'conditions', 'stats'));
    }
}
