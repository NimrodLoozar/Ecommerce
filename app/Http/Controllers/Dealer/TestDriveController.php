<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestDriveController extends Controller
{
    /**
     * Display a listing of test drive bookings for the dealer's cars.
     */
    public function index(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        $testDrives = TestDrive::whereHas('car', function ($query) use ($dealer) {
            $query->where('dealer_id', $dealer->id);
        })
            ->with(['user', 'car'])
            ->latest()
            ->paginate(15);

        return view('dealer.test-drives.index', compact('testDrives'));
    }

    /**
     * Update the test drive booking status.
     */
    public function update(Request $request, TestDrive $testDrive): RedirectResponse
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403);
        }

        // Verify test drive is for dealer's car
        if ($testDrive->car->dealer_id !== $dealer->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $testDrive->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        // TODO: Send confirmation email to customer

        return redirect()->route('dealer.test-drives.index')
            ->with('success', 'Test drive booking updated successfully!');
    }
}
