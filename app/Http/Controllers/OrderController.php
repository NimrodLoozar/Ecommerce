<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->with(['items.car.brand', 'items.car.carModel', 'shippingAddress'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        // Verify order belongs to user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load([
            'items.car.brand',
            'items.car.carModel',
            'items.car.images',
            'shippingAddress',
            'billingAddress',
            'payments',
            'leaseAgreement',
        ]);

        return view('orders.show', compact('order'));
    }
}
