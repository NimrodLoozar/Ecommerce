<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradeIn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TradeInController extends Controller
{
    public function index(): View
    {
        $tradeIns = TradeIn::with(['user', 'brand', 'carModel'])
            ->latest()
            ->paginate(20);

        return view('admin.trade-ins.index', compact('tradeIns'));
    }

    public function show(TradeIn $tradeIn): View
    {
        $tradeIn->load(['user', 'brand', 'carModel', 'images']);
        return view('admin.trade-ins.show', compact('tradeIn'));
    }

    public function update(Request $request, TradeIn $tradeIn): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,offer_made,accepted,rejected',
        ]);

        $tradeIn->update(['status' => $request->status]);

        return redirect()->route('admin.trade-ins.show', $tradeIn)
            ->with('success', 'Trade-in status updated successfully!');
    }
}
