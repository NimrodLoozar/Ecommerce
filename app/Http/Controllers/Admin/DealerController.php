<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DealerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DealerController extends Controller
{
    public function index(): View
    {
        $dealers = DealerProfile::with('user')
            ->withCount('cars')
            ->latest()
            ->paginate(20);

        return view('admin.dealers.index', compact('dealers'));
    }

    public function show(DealerProfile $dealer): View
    {
        $dealer->load(['user', 'cars', 'commissions']);
        return view('admin.dealers.show', compact('dealer'));
    }

    public function update(Request $request, DealerProfile $dealer): RedirectResponse
    {
        $request->validate([
            'is_approved' => 'required|boolean',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $dealer->update([
            'is_approved' => $request->boolean('is_approved'),
            'commission_rate' => $request->commission_rate,
        ]);

        // TODO: Send approval notification email to dealer

        return redirect()->route('admin.dealers.show', $dealer)
            ->with('success', 'Dealer updated successfully!');
    }
}
