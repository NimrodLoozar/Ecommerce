<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionController extends Controller
{
    public function index(): View
    {
        $commissions = Commission::with(['dealer.user', 'order'])
            ->latest()
            ->paginate(20);

        $totalPending = Commission::where('status', 'pending')->sum('amount');
        $totalPaid = Commission::where('status', 'paid')->sum('amount');

        return view('admin.commissions.index', compact('commissions', 'totalPending', 'totalPaid'));
    }

    public function show(Commission $commission): View
    {
        $commission->load(['dealer.user', 'order.orderItems']);
        return view('admin.commissions.show', compact('commission'));
    }

    public function update(Request $request, Commission $commission): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
            'paid_at' => 'nullable|date',
        ]);

        $commission->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'paid' ? ($request->paid_at ?? now()) : null,
        ]);

        return redirect()->route('admin.commissions.show', $commission)
            ->with('success', 'Commission status updated successfully!');
    }
}
