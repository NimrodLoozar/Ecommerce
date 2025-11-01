<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\View\View;

class CommissionController extends Controller
{
    /**
     * Display a listing of commissions for the dealer.
     */
    public function index(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        $commissions = Commission::where('dealer_id', $dealer->id)
            ->with(['order.user'])
            ->latest()
            ->paginate(15);

        // Calculate totals
        $totalEarned = Commission::where('dealer_id', $dealer->id)
            ->where('status', 'paid')
            ->sum('amount');

        $totalPending = Commission::where('dealer_id', $dealer->id)
            ->where('status', 'pending')
            ->sum('amount');

        return view('dealer.commissions.index', compact('commissions', 'totalEarned', 'totalPending'));
    }

    /**
     * Display the specified commission.
     */
    public function show(Commission $commission): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer || $commission->dealer_id !== $dealer->id) {
            abort(403);
        }

        $commission->load(['order.user', 'order.orderItems.car']);

        return view('dealer.commissions.show', compact('commission'));
    }
}
