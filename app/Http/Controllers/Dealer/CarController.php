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
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
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

        $validated = $request->validate([
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
            'vin' => 'required|string|max:17|unique:cars,vin',
            'license_plate' => 'nullable|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);

        // Validate images separately only if they exist
        if ($request->hasFile('images')) {
            $request->validate([
                'images' => 'array',
                'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048', // 2MB per image (PHP limit)
            ], [
                'images.*.image' => 'Each file must be a valid image.',
                'images.*.mimes' => 'Images must be in JPEG, JPG, PNG, or WEBP format.',
                'images.*.max' => 'Each image must not exceed 2MB. Please compress or resize your images.',
            ]);
        }

        // Generate title from brand, model, and year
        $brand = Brand::find($request->brand_id);
        $model = CarModel::find($request->car_model_id);
        $title = $request->year . ' ' . $brand->name . ' ' . $model->name;
        $slug = \Illuminate\Support\Str::slug($title) . '-' . uniqid();

        $car = Car::create([
            'user_id' => auth()->id(),
            'dealer_id' => $dealer->id,
            'brand_id' => $request->brand_id,
            'car_model_id' => $request->car_model_id,
            'category_id' => $request->category_id,
            'condition_id' => $request->condition_id,
            'title' => $title,
            'slug' => $slug,
            'year' => $request->year,
            'price' => $request->price,
            'lease_price_monthly' => $request->lease_price,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'engine_size' => $request->engine_size,
            'horsepower' => $request->horsepower,
            'exterior_color' => $request->exterior_color,
            'interior_color' => $request->interior_color,
            'doors' => $request->doors,
            'seats' => $request->seats,
            'vin' => $request->vin,
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

        // Handle image uploads - store in filesystem structure
        if ($request->hasFile('images')) {
            // Create folder structure: public/img/{Brand}/{Year Brand Model}/
            $brandName = $brand->name;
            $modelName = $model->name;
            
            // Check if brand folder exists (case-insensitive)
            $imgPath = public_path('img');
            $existingBrandFolder = null;
            if (file_exists($imgPath)) {
                $folders = scandir($imgPath);
                foreach ($folders as $folder) {
                    if ($folder !== '.' && $folder !== '..' && is_dir($imgPath . '/' . $folder)) {
                        if (strtolower($folder) === strtolower($brandName)) {
                            $existingBrandFolder = $folder;
                            break;
                        }
                    }
                }
            }
            
            // Use existing brand folder or create new one
            $brandFolderName = $existingBrandFolder ?: $brandName;
            // Include car ID to make folder unique
            $folderName = "{$car->year} {$brandName} {$modelName} - {$car->id}";
            $folderPath = "img/{$brandFolderName}/{$folderName}";
            $fullPath = public_path($folderPath);
            
            // Create directory if it doesn't exist
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            // Move uploaded images to the folder
            foreach ($request->file('images') as $index => $image) {
                $filename = 'image' . ($index + 1) . '.' . $image->getClientOriginalExtension();
                $image->move($fullPath, $filename);
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

        $car->load(['brand', 'carModel', 'category', 'condition', 'features']);
        
        // Get filesystem images
        $filesystemImages = $car->getFilesystemImages();

        return view('dealer.cars.show', compact('car', 'filesystemImages'));
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

        $brands = Brand::where('is_active', true)->orderBy('name')->get();
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

        $validated = $request->validate([
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
            'vin' => 'required|string|max:17|unique:cars,vin,' . $car->id,
            'license_plate' => 'nullable|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);

        // Validate images separately only if they exist
        if ($request->hasFile('images')) {
            $request->validate([
                'images' => 'array',
                'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048', // 2MB per image (PHP limit)
            ], [
                'images.*.image' => 'Each file must be a valid image.',
                'images.*.mimes' => 'Images must be in JPEG, JPG, PNG, or WEBP format.',
                'images.*.max' => 'Each image must not exceed 2MB. Please compress or resize your images.',
            ]);
        }

        // Update title if brand, model, or year changed
        if ($request->brand_id != $car->brand_id || $request->car_model_id != $car->car_model_id || $request->year != $car->year) {
            $brand = Brand::find($request->brand_id);
            $model = CarModel::find($request->car_model_id);
            $title = $request->year . ' ' . $brand->name . ' ' . $model->name;
            $slug = \Illuminate\Support\Str::slug($title) . '-' . uniqid();
        } else {
            $title = $car->title;
            $slug = $car->slug;
        }

        $car->update([
            'brand_id' => $request->brand_id,
            'car_model_id' => $request->car_model_id,
            'category_id' => $request->category_id,
            'condition_id' => $request->condition_id,
            'title' => $title,
            'slug' => $slug,
            'year' => $request->year,
            'price' => $request->price,
            'lease_price_monthly' => $request->lease_price,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'engine_size' => $request->engine_size,
            'horsepower' => $request->horsepower,
            'exterior_color' => $request->exterior_color,
            'interior_color' => $request->interior_color,
            'doors' => $request->doors,
            'seats' => $request->seats,
            'vin' => $request->vin,
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

        // Handle new image uploads - store in filesystem structure
        if ($request->hasFile('images')) {
            $brandObj = Brand::find($request->brand_id);
            $modelObj = CarModel::find($request->car_model_id);
            
            $brandName = $brandObj->name;
            $modelName = $modelObj->name;
            
            // Check if brand folder exists (case-insensitive)
            $imgPath = public_path('img');
            $existingBrandFolder = null;
            if (file_exists($imgPath)) {
                $folders = scandir($imgPath);
                foreach ($folders as $folder) {
                    if ($folder !== '.' && $folder !== '..' && is_dir($imgPath . '/' . $folder)) {
                        if (strtolower($folder) === strtolower($brandName)) {
                            $existingBrandFolder = $folder;
                            break;
                        }
                    }
                }
            }
            
            // Use existing brand folder or create new one
            $brandFolderName = $existingBrandFolder ?: $brandName;
            // Include car ID to make folder unique
            $folderName = "{$request->year} {$brandName} {$modelName} - {$car->id}";
            $folderPath = "img/{$brandFolderName}/{$folderName}";
            $fullPath = public_path($folderPath);
            
            // Create directory if it doesn't exist
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            // Count existing images to continue numbering
            $existingFiles = glob($fullPath . '/image*');
            $startIndex = count($existingFiles) + 1;
            
            // Move uploaded images to the folder
            foreach ($request->file('images') as $index => $image) {
                $filename = 'image' . ($startIndex + $index) . '.' . $image->getClientOriginalExtension();
                $image->move($fullPath, $filename);
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

        // Note: We don't delete the filesystem images folder as it might be shared
        // or contain images for other listings. Manual cleanup can be done if needed.

        $car->delete();

        return redirect()->route('dealer.cars.index')
            ->with('success', 'Car deleted successfully!');
    }

    /**
     * Get car models for a specific brand (AJAX endpoint).
     */
    public function getModelsByBrand(Brand $brand)
    {
        $models = CarModel::where('brand_id', $brand->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($models);
    }
}
