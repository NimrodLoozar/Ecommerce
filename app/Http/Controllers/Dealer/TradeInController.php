<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\TradeIn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TradeInController extends Controller
{
    /**
     * Display a listing of trade-in requests for the dealer.
     */
    public function index(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        // Show all trade-ins or filter by dealer if needed
        // For now, showing all trade-ins (dealer can review and make offers)
        $tradeIns = TradeIn::with(['user', 'brand', 'carModel', 'images'])
            ->latest()
            ->paginate(15);

        return view('dealer.trade-ins.index', compact('tradeIns'));
    }

    /**
     * Display the specified trade-in request.
     */
    public function show(TradeIn $tradeIn): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403);
        }

        $tradeIn->load(['user', 'brand', 'carModel', 'images']);

        return view('dealer.trade-ins.show', compact('tradeIn'));
    }

    /**
     * Update the trade-in request with an offer.
     */
    public function update(Request $request, TradeIn $tradeIn): RedirectResponse
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,offer_made,accepted,rejected',
            'offered_price' => 'nullable|numeric|min:0',
            'dealer_notes' => 'nullable|string|max:2000',
        ]);

        $tradeIn->update([
            'status' => $request->status,
            'offered_price' => $request->offered_price,
            'dealer_notes' => $request->dealer_notes,
        ]);

        // TODO: Send offer notification email to customer

        return redirect()->route('dealer.trade-ins.show', $tradeIn)
            ->with('success', 'Trade-in offer updated successfully!');
    }
}
