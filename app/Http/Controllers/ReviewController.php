<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a newly created review.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:100',
            'comment' => 'required|string|max:1000',
        ]);

        $car = Car::findOrFail($request->car_id);

        // Check if user already reviewed this car
        $existing = auth()->user()
            ->reviews()
            ->where('car_id', $car->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this car.');
        }

        auth()->user()->reviews()->create([
            'car_id' => $car->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_approved' => false, // Requires admin approval
        ]);

        return back()->with('success', 'Review submitted! It will appear after approval.');
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review): RedirectResponse
    {
        // Verify review belongs to user
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:100',
            'comment' => 'required|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_approved' => false, // Reset approval status
        ]);

        return back()->with('success', 'Review updated! It will appear after re-approval.');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review): RedirectResponse
    {
        // Verify review belongs to user
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
