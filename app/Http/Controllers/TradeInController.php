<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\TradeIn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TradeInController extends Controller
{
    /**
     * Display a listing of the user's trade-in submissions.
     */
    public function index(): View
    {
        $tradeIns = auth()->user()
            ->tradeIns()
            ->with(['brand', 'carModel'])
            ->latest()
            ->paginate(10);

        return view('trade-ins.index', compact('tradeIns'));
    }

    /**
     * Show the form for creating a new trade-in submission.
     */
    public function create(): View
    {
        $brands = Brand::orderBy('name')->get();

        return view('trade-ins.create', compact('brands'));
    }

    /**
     * Store a newly created trade-in submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'car_model_id' => 'required|exists:car_models,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'condition' => 'required|in:excellent,good,fair,poor',
            'exterior_color' => 'required|string|max:50',
            'interior_color' => 'required|string|max:50',
            'vin_number' => 'nullable|string|max:17',
            'license_plate' => 'nullable|string|max:20',
            'ownership_status' => 'required|in:owned,financed,leased',
            'accidents' => 'required|boolean',
            'service_history' => 'required|in:full,partial,none',
            'description' => 'nullable|string|max:2000',
            'estimated_value' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|max:5120', // 5MB max per image
        ]);

        $tradeIn = auth()->user()->tradeIns()->create([
            'brand_id' => $request->brand_id,
            'car_model_id' => $request->car_model_id,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'condition' => $request->condition,
            'exterior_color' => $request->exterior_color,
            'interior_color' => $request->interior_color,
            'vin_number' => $request->vin_number,
            'license_plate' => $request->license_plate,
            'ownership_status' => $request->ownership_status,
            'accidents' => $request->boolean('accidents'),
            'service_history' => $request->service_history,
            'description' => $request->description,
            'estimated_value' => $request->estimated_value,
            'status' => 'pending',
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('trade-ins', 'public');

                $tradeIn->images()->create([
                    'image_path' => $path,
                    'type' => $index === 0 ? 'exterior' : 'interior',
                    'display_order' => $index + 1,
                ]);
            }
        }

        // TODO: Send notification email to admin/dealer

        return redirect()->route('trade-ins.show', $tradeIn)
            ->with('success', 'Trade-in submission received! We will review and contact you soon.');
    }

    /**
     * Display the specified trade-in submission.
     */
    public function show(TradeIn $tradeIn): View
    {
        // Verify trade-in belongs to user
        if ($tradeIn->user_id !== auth()->id()) {
            abort(403);
        }

        $tradeIn->load(['brand', 'carModel', 'images']);

        return view('trade-ins.show', compact('tradeIn'));
    }
}