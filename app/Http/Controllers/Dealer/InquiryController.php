<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    /**
     * Display a listing of inquiries for the dealer's cars.
     */
    public function index(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        $inquiries = Inquiry::whereHas('car', function ($query) use ($dealer) {
            $query->where('dealer_id', $dealer->id);
        })
            ->with(['user', 'car'])
            ->latest()
            ->paginate(15);

        return view('dealer.inquiries.index', compact('inquiries'));
    }

    /**
     * Display the specified inquiry.
     */
    public function show(Inquiry $inquiry): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403);
        }

        // Verify inquiry is for dealer's car
        if ($inquiry->car->dealer_id !== $dealer->id) {
            abort(403);
        }

        $inquiry->load(['user', 'car']);

        return view('dealer.inquiries.show', compact('inquiry'));
    }

    /**
     * Update the inquiry status or add a response.
     */
    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403);
        }

        // Verify inquiry is for dealer's car
        if ($inquiry->car->dealer_id !== $dealer->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:new,in_progress,resolved,converted',
            'response' => 'nullable|string|max:2000',
        ]);

        $inquiry->update([
            'status' => $request->status,
            'response' => $request->response,
        ]);

        // TODO: Send response email to customer

        return redirect()->route('dealer.inquiries.show', $inquiry)
            ->with('success', 'Inquiry updated successfully!');
    }
}
