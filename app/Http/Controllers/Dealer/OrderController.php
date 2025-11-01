<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the dealer's cars.
     */
    public function index(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        $orders = Order::whereHas('orderItems.car', function ($query) use ($dealer) {
            $query->where('dealer_id', $dealer->id);
        })
            ->with(['user', 'orderItems.car', 'address'])
            ->latest()
            ->paginate(15);

        return view('dealer.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403);
        }

        // Verify order contains at least one item from this dealer
        $hasDealerItems = $order->orderItems()
            ->whereHas('car', function ($query) use ($dealer) {
                $query->where('dealer_id', $dealer->id);
            })
            ->exists();

        if (!$hasDealerItems) {
            abort(403);
        }

        $order->load(['user', 'orderItems.car', 'address', 'payments']);

        return view('dealer.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403);
        }

        // Verify order contains items from this dealer
        $hasDealerItems = $order->orderItems()
            ->whereHas('car', function ($query) use ($dealer) {
                $query->where('dealer_id', $dealer->id);
            })
            ->exists();

        if (!$hasDealerItems) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        // TODO: Send status update email to customer

        return redirect()->route('dealer.orders.show', $order)
            ->with('success', 'Order status updated successfully!');
    }
}
