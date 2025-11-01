<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index(): View
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with(['items.car.brand', 'items.car.carModel', 'items.car.images'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $cartItems = $cart->items;

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->car->price * $item->quantity;
        });

        $shipping = 0; // Free shipping or calculate based on your logic
        $tax = $subtotal * 0.21; // 21% VAT
        $total = $subtotal + $shipping + $tax;

        // Get user's addresses
        $addresses = auth()->user()->addresses;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total', 'addresses'));
    }

    /**
     * Process the checkout and create order.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_same_as_shipping' => 'nullable|boolean',
            'billing_address_id' => 'required_if:billing_same_as_shipping,false|nullable|exists:addresses,id',
            'payment_method' => 'required|in:card,bank_transfer,cash',
            'card_number' => 'required_if:payment_method,card|nullable|string',
            'name_on_card' => 'required_if:payment_method,card|nullable|string',
            'expiration_date' => 'required_if:payment_method,card|nullable|string',
            'cvc' => 'required_if:payment_method,card|nullable|string',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with('items.car')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Verify addresses belong to user
        $shippingAddress = Address::where('id', $request->shipping_address_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $billingAddressId = $request->filled('billing_same_as_shipping')
            ? $request->shipping_address_id
            : $request->billing_address_id;

        // Calculate totals
        $subtotal = $cart->items->sum(function ($item) {
            return $item->car->price * $item->quantity;
        });

        $shipping = 0;
        $tax = $subtotal * 0.21;
        $total = $subtotal + $shipping + $tax;

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $billingAddressId,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'tax' => $tax,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'car_id' => $item->car_id,
                    'quantity' => $item->quantity,
                    'price' => $item->car->price,
                    'subtotal' => $item->car->price * $item->quantity,
                ]);

                // Update car stock
                $item->car->decrement('stock_quantity', $item->quantity);

                // Update car status if out of stock
                if ($item->car->fresh()->stock_quantity <= 0) {
                    $item->car->update(['status' => 'sold']);
                }
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            // Clear cart
            $cart->items()->delete();
            $cart->update(['status' => 'completed']);

            DB::commit();

            // TODO: Send order confirmation email
            // TODO: Process payment based on payment method

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully! Order number: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to process order. Please try again.');
        }
    }
}
