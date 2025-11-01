<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index(): View
    {
        $wishlists = auth()->user()
            ->wishlists()
            ->with(['car.brand', 'car.carModel', 'car.images'])
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Add a car to wishlist.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
        ]);

        $car = Car::findOrFail($request->car_id);

        // Check if already in wishlist
        $existing = auth()->user()
            ->wishlists()
            ->where('car_id', $car->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Car is already in your wishlist.');
        }

        auth()->user()->wishlists()->create([
            'car_id' => $car->id,
        ]);

        return back()->with('success', 'Car added to wishlist!');
    }

    /**
     * Remove a car from wishlist.
     */
    public function destroy(Wishlist $wishlist): RedirectResponse
    {
        // Verify wishlist belongs to user
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        $wishlist->delete();

        return back()->with('success', 'Car removed from wishlist.');
    }
}
