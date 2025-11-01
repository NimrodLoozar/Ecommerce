<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Get authenticated user's wishlist.
     */
    public function index(Request $request): JsonResponse
    {
        $wishlists = $request->user()
            ->wishlists()
            ->with(['car.brand', 'car.carModel', 'car.images'])
            ->latest()
            ->paginate(15);

        return response()->json($wishlists);
    }

    /**
     * Add a car to wishlist.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
        ]);

        // Check if already in wishlist
        $existing = Wishlist::where('user_id', $request->user()->id)
            ->where('car_id', $request->car_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Car is already in your wishlist',
                'wishlist' => $existing->load('car'),
            ], 200);
        }

        $wishlist = Wishlist::create([
            'user_id' => $request->user()->id,
            'car_id' => $request->car_id,
        ]);

        $wishlist->load('car');

        return response()->json([
            'message' => 'Car added to wishlist successfully',
            'wishlist' => $wishlist,
        ], 201);
    }

    /**
     * Remove a car from wishlist.
     */
    public function destroy(Request $request, Wishlist $wishlist): JsonResponse
    {
        // Verify wishlist belongs to user
        if ($wishlist->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $wishlist->delete();

        return response()->json([
            'message' => 'Car removed from wishlist successfully',
        ]);
    }
}
