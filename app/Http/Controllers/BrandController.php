<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Display a listing of all brands.
     */
    public function index(): View
    {
        $brands = Brand::withCount(['cars' => function ($query) {
            $query->available();
        }])
            ->orderBy('name')
            ->get();

        return view('brands.index', compact('brands'));
    }

    /**
     * Display cars for a specific brand.
     */
    public function show(Request $request, Brand $brand): View
    {
        $query = $brand->cars()
            ->with(['carModel', 'category', 'condition', 'images'])
            ->available();

        // Apply filters
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
        $categories = Category::orderBy('name')->get();
        $conditions = Condition::orderBy('name')->get();

        // Get total cars count for this brand
        $carsCount = $brand->cars()->available()->count();

        return view('brands.show', compact('brand', 'cars', 'categories', 'conditions', 'carsCount'));
    }
}
