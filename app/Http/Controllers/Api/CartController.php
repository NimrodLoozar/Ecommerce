<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get the authenticated user's cart.
     */
    public function index(Request $request): JsonResponse
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $cart->load(['cartItems.car.brand', 'cartItems.car.carModel', 'cartItems.car.images']);

        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->car->price * $item->quantity;
        });

        $vatRate = 0.21; // 21% VAT
        $vat = $subtotal * $vatRate;
        $total = $subtotal + $vat;

        return response()->json([
            'cart' => $cart,
            'summary' => [
                'subtotal' => $subtotal,
                'vat' => $vat,
                'vat_rate' => $vatRate,
                'total' => $total,
            ],
        ]);
    }

    /**
     * Add an item to the cart.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        // Check if item already exists
        $existingItem = $cart->cartItems()->where('car_id', $request->car_id)->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity,
            ]);
            $cartItem = $existingItem;
        } else {
            $cartItem = $cart->cartItems()->create([
                'car_id' => $request->car_id,
                'quantity' => $request->quantity,
            ]);
        }

        $cartItem->load('car');

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_item' => $cartItem,
        ], 201);
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        // Verify cart item belongs to user
        if ($cartItem->cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'message' => 'Cart item updated successfully',
            'cart_item' => $cartItem->fresh('car'),
        ]);
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(Request $request, CartItem $cartItem): JsonResponse
    {
        // Verify cart item belongs to user
        if ($cartItem->cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully',
        ]);
    }
}
