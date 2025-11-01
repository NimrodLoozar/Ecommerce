<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Feature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CarController extends Controller
{
    /**
     * Display a listing of the dealer's cars.
     */
    public function index(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        $cars = Car::where('dealer_id', $dealer->id)
            ->with(['brand', 'carModel', 'category', 'condition', 'images'])
            ->latest()
            ->paginate(15);

        return view('dealer.cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new car.
     */
    public function create(): View
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $conditions = Condition::orderBy('name')->get();
        $features = Feature::orderBy('name')->get();

        return view('dealer.cars.create', compact('brands', 'categories', 'conditions', 'features'));
    }

    /**
     * Store a newly created car in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to add cars.');
        }

        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'car_model_id' => 'required|exists:car_models,id',
            'category_id' => 'required|exists:categories,id',
            'condition_id' => 'required|exists:conditions,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'lease_price' => 'nullable|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid,plugin_hybrid',
            'transmission' => 'required|in:manual,automatic,semi_automatic',
            'engine_size' => 'nullable|numeric|min:0',
            'horsepower' => 'nullable|integer|min:0',
            'exterior_color' => 'required|string|max:50',
            'interior_color' => 'required|string|max:50',
            'doors' => 'required|integer|min:2|max:5',
            'seats' => 'required|integer|min:2|max:9',
            'vin_number' => 'required|string|max:17|unique:cars,vin_number',
            'license_plate' => 'nullable|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
            'images.*' => 'nullable|image|max:5120', // 5MB per image
        ]);

        $car = Car::create([
            'dealer_id' => $dealer->id,
            'brand_id' => $request->brand_id,
            'car_model_id' => $request->car_model_id,
            'category_id' => $request->category_id,
            'condition_id' => $request->condition_id,
            'year' => $request->year,
            'price' => $request->price,
            'lease_price' => $request->lease_price,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'engine_size' => $request->engine_size,
            'horsepower' => $request->horsepower,
            'exterior_color' => $request->exterior_color,
            'interior_color' => $request->interior_color,
            'doors' => $request->doors,
            'seats' => $request->seats,
            'vin_number' => $request->vin_number,
            'license_plate' => $request->license_plate,
            'stock_quantity' => $request->stock_quantity,
            'status' => $request->stock_quantity > 0 ? 'available' : 'sold',
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        // Attach features
        if ($request->has('features')) {
            $car->features()->attach($request->features);
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('cars', 'public');

                $car->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'display_order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('dealer.cars.show', $car)
            ->with('success', 'Car added successfully!');
    }

    /**
     * Display the specified car.
     */
    public function show(Car $car): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer || $car->dealer_id !== $dealer->id) {
            abort(403);
        }

        $car->load(['brand', 'carModel', 'category', 'condition', 'images', 'features']);

        return view('dealer.cars.show', compact('car'));
    }

    /**
     * Show the form for editing the specified car.
     */
    public function edit(Car $car): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer || $car->dealer_id !== $dealer->id) {
            abort(403);
        }

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $conditions = Condition::orderBy('name')->get();
        $features = Feature::orderBy('name')->get();

        $car->load('features', 'images');

        return view('dealer.cars.edit', compact('car', 'brands', 'categories', 'conditions', 'features'));
    }

    /**
     * Update the specified car in storage.
     */
    public function update(Request $request, Car $car): RedirectResponse
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer || $car->dealer_id !== $dealer->id) {
            abort(403);
        }

        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'car_model_id' => 'required|exists:car_models,id',
            'category_id' => 'required|exists:categories,id',
            'condition_id' => 'required|exists:conditions,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'lease_price' => 'nullable|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid,plugin_hybrid',
            'transmission' => 'required|in:manual,automatic,semi_automatic',
            'engine_size' => 'nullable|numeric|min:0',
            'horsepower' => 'nullable|integer|min:0',
            'exterior_color' => 'required|string|max:50',
            'interior_color' => 'required|string|max:50',
            'doors' => 'required|integer|min:2|max:5',
            'seats' => 'required|integer|min:2|max:9',
            'vin_number' => 'required|string|max:17|unique:cars,vin_number,' . $car->id,
            'license_plate' => 'nullable|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $car->update([
            'brand_id' => $request->brand_id,
            'car_model_id' => $request->car_model_id,
            'category_id' => $request->category_id,
            'condition_id' => $request->condition_id,
            'year' => $request->year,
            'price' => $request->price,
            'lease_price' => $request->lease_price,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'engine_size' => $request->engine_size,
            'horsepower' => $request->horsepower,
            'exterior_color' => $request->exterior_color,
            'interior_color' => $request->interior_color,
            'doors' => $request->doors,
            'seats' => $request->seats,
            'vin_number' => $request->vin_number,
            'license_plate' => $request->license_plate,
            'stock_quantity' => $request->stock_quantity,
            'status' => $request->stock_quantity > 0 ? 'available' : 'sold',
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        // Sync features
        if ($request->has('features')) {
            $car->features()->sync($request->features);
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $currentImagesCount = $car->images()->count();

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('cars', 'public');

                $car->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                    'display_order' => $currentImagesCount + $index + 1,
                ]);
            }
        }

        return redirect()->route('dealer.cars.show', $car)
            ->with('success', 'Car updated successfully!');
    }

    /**
     * Remove the specified car from storage.
     */
    public function destroy(Car $car): RedirectResponse
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer || $car->dealer_id !== $dealer->id) {
            abort(403);
        }

        // Delete car images from storage
        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $car->delete();

        return redirect()->route('dealer.cars.index')
            ->with('success', 'Car deleted successfully!');
    }
}
