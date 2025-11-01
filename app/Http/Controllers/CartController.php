<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index(): View
    {
        $cart = $this->getOrCreateCart();

        $cart->load(['items.car.brand', 'items.car.carModel', 'items.car.images']);

        $cartItems = $cart->items;

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->car->price * $item->quantity;
        });

        $shipping = 0; // Free shipping or calculate based on your logic
        $tax = $subtotal * 0.21; // 21% VAT (adjust based on country)
        $total = $subtotal + $shipping + $tax;

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    /**
     * Add a car to the cart.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'quantity' => 'integer|min:1|max:10',
        ]);

        $car = Car::findOrFail($request->car_id);

        // Check if car is available
        if ($car->status !== 'available' || $car->stock_quantity < 1) {
            return back()->with('error', 'This car is no longer available.');
        }

        $cart = $this->getOrCreateCart();

        // Check if car already in cart
        $existingItem = $cart->items()->where('car_id', $car->id)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + ($request->quantity ?? 1);

            // Check stock
            if ($newQuantity > $car->stock_quantity) {
                return back()->with('error', 'Not enough stock available.');
            }

            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'car_id' => $car->id,
                'quantity' => $request->quantity ?? 1,
                'price' => $car->price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Car added to cart!');
    }

    /**
     * Update cart item quantities.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1|max:10',
        ]);

        $cart = $this->getOrCreateCart();

        foreach ($request->quantities as $itemId => $quantity) {
            $cartItem = CartItem::find($itemId);

            // Verify cart item exists and belongs to user's cart
            if (!$cartItem || $cartItem->cart_id !== $cart->id) {
                continue;
            }

            // Check stock
            if ($quantity > $cartItem->car->stock_quantity) {
                return back()->with('error', 'Not enough stock available for ' . $cartItem->car->title);
            }

            $cartItem->update(['quantity' => $quantity]);
        }

        return back()->with('success', 'Cart updated!');
    }

    /**
     * Remove item from cart.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);

        // Verify cart item belongs to user's cart
        $cart = $this->getOrCreateCart();
        if ($cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Get or create cart for current user.
     */
    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => auth()->id(),
            'status' => 'active',
        ]);
    }
}
