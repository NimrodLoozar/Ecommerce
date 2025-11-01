<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarController extends Controller
{
    public function index(): View
    {
        $cars = Car::with(['brand', 'carModel', 'category', 'dealer'])
            ->latest()
            ->paginate(20);

        return view('admin.cars.index', compact('cars'));
    }

    public function show(Car $car): View
    {
        $car->load(['brand', 'carModel', 'category', 'condition', 'dealer', 'images', 'features']);
        return view('admin.cars.show', compact('car'));
    }

    public function update(Request $request, Car $car): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:available,sold,reserved',
            'is_featured' => 'nullable|boolean',
        ]);

        $car->update([
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.cars.show', $car)
            ->with('success', 'Car updated successfully!');
    }

    public function destroy(Car $car): RedirectResponse
    {
        // Check if car has orders
        if ($car->orderItems()->count() > 0) {
            return redirect()->route('admin.cars.index')
                ->with('error', 'Cannot delete car with existing orders.');
        }

        $car->delete();

        return redirect()->route('admin.cars.index')
            ->with('success', 'Car deleted successfully!');
    }
}
