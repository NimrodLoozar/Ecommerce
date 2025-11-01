<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Get a paginated list of cars with filters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Car::with(['brand', 'carModel', 'category', 'condition', 'images'])
            ->where('status', 'available');

        // Apply filters
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->has('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->has('min_year')) {
            $query->where('year', '>=', $request->min_year);
        }

        if ($request->has('max_year')) {
            $query->where('year', '<=', $request->max_year);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = min($request->get('per_page', 15), 50); // Max 50 per page
        $cars = $query->paginate($perPage);

        return response()->json($cars);
    }

    /**
     * Get a single car by ID.
     */
    public function show(Car $car): JsonResponse
    {
        $car->load([
            'brand',
            'carModel',
            'category',
            'condition',
            'dealer',
            'images',
            'features',
            'reviews' => function ($query) {
                $query->where('is_approved', true)->with('user');
            }
        ]);

        // Increment views
        $car->increment('views');

        return response()->json([
            'car' => $car,
        ]);
    }
}
