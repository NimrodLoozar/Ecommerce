<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use App\Services\MolliePaymentService;
use App\Services\PayPalPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;
    protected MolliePaymentService $mollieService;
    protected PayPalPaymentService $paypalService;

    public function __construct(
        PaymentService $paymentService,
        MolliePaymentService $mollieService,
        PayPalPaymentService $paypalService
    ) {
        $this->paymentService = $paymentService;
        $this->mollieService = $mollieService;
        $this->paypalService = $paypalService;
    }

    /**
     * Create a payment intent for an order.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('user')->findOrFail($request->order_id);

        // Verify order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 403);
        }

        // Check if order is already paid
        if ($order->payment_status === 'paid') {
            return response()->json([
                'error' => 'Order already paid',
            ], 400);
        }

        $result = $this->paymentService->createPaymentIntent($order);

        if ($result['success']) {
            return response()->json([
                'clientSecret' => $result['client_secret'],
                'paymentIntentId' => $result['payment_intent_id'],
            ]);
        }

        return response()->json([
            'error' => $result['error'] ?? 'Failed to create payment intent',
        ], 500);
    }

    /**
     * Confirm payment success.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        $result = $this->paymentService->confirmPayment($request->payment_intent_id);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'order' => $result['order'],
                'redirect_url' => route('orders.show', $result['order']),
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'] ?? 'Payment confirmation failed',
        ], 400);
    }

    /**
     * Handle Stripe webhook events.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function webhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (!$signature) {
            Log::warning('Webhook received without signature');
            return response()->json(['error' => 'Missing signature'], 400);
        }

        $result = $this->paymentService->handleWebhook($payload, $signature);

        if ($result['success']) {
            return response()->json(['received' => true]);
        }

        return response()->json([
            'error' => $result['error'] ?? 'Webhook handling failed',
        ], 400);
    }

    /**
     * Create a refund for an order.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function refund(Request $request, Order $order): JsonResponse
    {
        // TODO: Add authorization check (admin only)
        
        $request->validate([
            'amount' => 'nullable|numeric|min:0.01|max:' . $order->total,
        ]);

        $result = $this->paymentService->createRefund(
            $order,
            $request->input('amount')
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'] ?? 'Refund failed',
        ], 400);
    }

    /**
     * Create a Mollie payment.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createMolliePayment(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'nullable|string', // ideal, creditcard, bancontact, etc.
        ]);

        $order = Order::with('user')->findOrFail($request->order_id);

        // Verify order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $result = $this->mollieService->createPayment($order, $request->method);

            return response()->json([
                'success' => true,
                'checkoutUrl' => $result['checkoutUrl'],
                'paymentId' => $result['paymentId'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Handle Mollie webhook.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function mollieWebhook(Request $request): JsonResponse
    {
        $paymentId = $request->input('id');

        if (!$paymentId) {
            return response()->json(['error' => 'Missing payment ID'], 400);
        }

        try {
            $this->mollieService->handleWebhook($paymentId);
            return response()->json(['received' => true]);

        } catch (\Exception $e) {
            Log::error('Mollie webhook handling failed', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Webhook handling failed'], 400);
        }
    }

    /**
     * Create a PayPal order.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createPayPalOrder(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with(['user', 'items.car.brand'])->findOrFail($request->order_id);

        // Verify order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $result = $this->paypalService->createOrder($order);

            return response()->json([
                'success' => true,
                'orderId' => $result['orderId'],
                'approvalUrl' => $result['approvalUrl'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Capture PayPal order (after customer approval).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function capturePayPalOrder(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        try {
            $result = $this->paypalService->captureOrder($request->order_id);

            return response()->json([
                'success' => true,
                'captureId' => $result['captureId'],
                'status' => $result['status'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Handle PayPal success redirect.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function paypalSuccess(Request $request): RedirectResponse
    {
        $token = $request->query('token'); // PayPal order ID

        if (!$token) {
            return redirect()->route('cart.index')
                ->with('error', 'Payment verification failed');
        }

        try {
            // Capture the payment
            $result = $this->paypalService->captureOrder($token);

            // Get the order from payment record
            $payment = \App\Models\Payment::where('transaction_id', $token)->first();

            if ($payment && $payment->order) {
                return redirect()->route('orders.show', $payment->order)
                    ->with('success', 'Payment successful! Order confirmed.');
            }

            return redirect()->route('orders.index')
                ->with('success', 'Payment successful!');

        } catch (\Exception $e) {
            Log::error('PayPal success handling failed', [
                'token' => $token,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('cart.index')
                ->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Handle PayPal cancel redirect.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function paypalCancel(Request $request): RedirectResponse
    {
        $token = $request->query('token');

        Log::info('PayPal payment cancelled', ['token' => $token]);

        return redirect()->route('checkout.index')
            ->with('error', 'Payment was cancelled. Please try again.');
    }
}
