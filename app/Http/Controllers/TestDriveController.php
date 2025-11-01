<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\TestDrive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestDriveController extends Controller
{
    /**
     * Display a listing of the user's test drive bookings.
     */
    public function index(): View
    {
        $testDrives = auth()->user()
            ->testDrives()
            ->with(['car.brand', 'car.carModel'])
            ->latest()
            ->paginate(10);

        return view('test-drives.index', compact('testDrives'));
    }

    /**
     * Store a newly created test drive booking.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|date_format:H:i',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $car = Car::findOrFail($request->car_id);

        // Check if user already has a pending test drive for this car
        $existing = auth()->user()
            ->testDrives()
            ->where('car_id', $car->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a test drive booking for this car.');
        }

        auth()->user()->testDrives()->create([
            'car_id' => $car->id,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'phone' => $request->phone,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // TODO: Send confirmation email

        return back()->with('success', 'Test drive booking submitted! We will confirm soon.');
    }

    /**
     * Update test drive booking (for cancellation by customer).
     */
    public function update(Request $request, TestDrive $testDrive): RedirectResponse
    {
        // Verify test drive belongs to user
        if ($testDrive->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:cancelled',
        ]);

        // Only allow cancellation if not completed or already cancelled
        if (in_array($testDrive->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Cannot cancel this test drive.');
        }

        $testDrive->update(['status' => 'cancelled']);

        return back()->with('success', 'Test drive cancelled.');
    }
}
