<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Get authenticated user's orders.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()
            ->orders()
            ->with(['orderItems.car', 'address'])
            ->latest()
            ->paginate(15);

        return response()->json($orders);
    }

    /**
     * Create a new order from cart.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,bank_transfer,cash',
        ]);

        // Get user's cart
        $cart = Cart::where('user_id', $request->user()->id)->first();

        if (!$cart || $cart->cartItems()->count() === 0) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 400);
        }

        $cart->load('cartItems.car');

        // Calculate totals
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->car->price * $item->quantity;
        });

        $vatRate = 0.21;
        $vat = $subtotal * $vatRate;
        $total = $subtotal + $vat;

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'address_id' => $request->address_id,
                'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
                'subtotal' => $subtotal,
                'tax_amount' => $vat,
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($cart->cartItems as $cartItem) {
                $order->orderItems()->create([
                    'car_id' => $cartItem->car_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->car->price,
                ]);

                // Update car stock
                $car = $cartItem->car;
                $car->decrement('stock_quantity', $cartItem->quantity);

                if ($car->stock_quantity <= 0) {
                    $car->update(['status' => 'sold']);
                }
            }

            // Create payment record
            $order->payments()->create([
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'status' => 'pending',
            ]);

            // Clear cart
            $cart->cartItems()->delete();

            DB::commit();

            $order->load(['orderItems.car', 'address', 'payments']);

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific order.
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        // Verify order belongs to user
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->load(['orderItems.car', 'address', 'payments']);

        return response()->json([
            'order' => $order,
        ]);
    }
}
