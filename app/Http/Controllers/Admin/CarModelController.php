<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarModelController extends Controller
{
    public function index(): View
    {
        $carModels = CarModel::with('brand')->withCount('cars')->latest()->paginate(20);
        return view('admin.car-models.index', compact('carModels'));
    }

    public function create(): View
    {
        $brands = Brand::orderBy('name')->get();
        return view('admin.car-models.create', compact('brands'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        CarModel::create($request->only(['brand_id', 'name', 'description']));

        return redirect()->route('admin.car-models.index')
            ->with('success', 'Car model created successfully!');
    }

    public function edit(CarModel $carModel): View
    {
        $brands = Brand::orderBy('name')->get();
        return view('admin.car-models.edit', compact('carModel', 'brands'));
    }

    public function update(Request $request, CarModel $carModel): RedirectResponse
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $carModel->update($request->only(['brand_id', 'name', 'description']));

        return redirect()->route('admin.car-models.index')
            ->with('success', 'Car model updated successfully!');
    }

    public function destroy(CarModel $carModel): RedirectResponse
    {
        if ($carModel->cars()->count() > 0) {
            return redirect()->route('admin.car-models.index')
                ->with('error', 'Cannot delete car model with existing cars.');
        }

        $carModel->delete();

        return redirect()->route('admin.car-models.index')
            ->with('success', 'Car model deleted successfully!');
    }
}
