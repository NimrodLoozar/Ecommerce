<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeatureController extends Controller
{
    public function index(): View
    {
        $features = Feature::withCount('cars')->latest()->paginate(20);
        return view('admin.features.index', compact('features'));
    }

    public function create(): View
    {
        return view('admin.features.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:features,name',
            'description' => 'nullable|string',
        ]);

        Feature::create($request->only(['name', 'description']));

        return redirect()->route('admin.features.index')
            ->with('success', 'Feature created successfully!');
    }

    public function edit(Feature $feature): View
    {
        return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, Feature $feature): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:features,name,' . $feature->id,
            'description' => 'nullable|string',
        ]);

        $feature->update($request->only(['name', 'description']));

        return redirect()->route('admin.features.index')
            ->with('success', 'Feature updated successfully!');
    }

    public function destroy(Feature $feature): RedirectResponse
    {
        if ($feature->cars()->count() > 0) {
            return redirect()->route('admin.features.index')
                ->with('error', 'Cannot delete feature with existing cars.');
        }

        $feature->delete();

        return redirect()->route('admin.features.index')
            ->with('success', 'Feature deleted successfully!');
    }
}
