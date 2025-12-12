<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Display the user's saved payment methods.
     */
    public function index(): View
    {
        $paymentMethods = auth()->user()->paymentMethods()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Save a new payment method.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'payment_method_id' => 'required|string',
            'set_as_default' => 'nullable|boolean',
        ]);

        try {
            $paymentMethod = $this->paymentService->savePaymentMethod(
                auth()->user(),
                $request->payment_method_id,
                $request->boolean('set_as_default', false)
            );

            return response()->json([
                'message' => 'Payment method saved successfully',
                'payment_method' => $paymentMethod,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save payment method',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Set a payment method as default.
     */
    public function setDefault(PaymentMethod $paymentMethod): RedirectResponse
    {
        // Verify ownership
        if ($paymentMethod->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $this->paymentService->setDefaultPaymentMethod($paymentMethod);

        return back()->with('success', 'Default payment method updated');
    }

    /**
     * Delete a saved payment method.
     */
    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        // Verify ownership
        if ($paymentMethod->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        try {
            $this->paymentService->deletePaymentMethod($paymentMethod);

            return back()->with('success', 'Payment method deleted');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete payment method');
        }
    }
}
