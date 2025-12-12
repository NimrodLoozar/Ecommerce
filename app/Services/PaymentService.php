<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment as PaymentModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\User;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected string $currency;

    public function __construct()
    {
        Stripe::setApiKey(config('payment.stripe.secret_key'));
        $this->currency = config('payment.currency', 'eur');
    }

    /**
     * Create a Stripe PaymentIntent for an order.
     *
     * @param Order $order
     * @return array
     * @throws ApiErrorException
     */
    public function createPaymentIntent(Order $order): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToStripeAmount($order->total),
                'currency' => $this->currency,
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_email' => $order->user->email,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'description' => 'Order #' . $order->order_number,
            ]);

            // Update payment record with PaymentIntent ID
            PaymentModel::where('order_id', $order->id)
                ->update([
                    'transaction_id' => $paymentIntent->id,
                    'status' => 'pending',
                ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe PaymentIntent creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirm a payment and update order status.
     *
     * @param string $paymentIntentId
     * @return array
     */
    public function confirmPayment(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                $orderId = $paymentIntent->metadata->order_id ?? null;

                if ($orderId) {
                    $order = Order::find($orderId);

                    if ($order) {
                        // Update order status
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'confirmed',
                        ]);

                        // Update payment record
                        PaymentModel::where('order_id', $order->id)
                            ->update([
                                'status' => 'completed',
                                'transaction_id' => $paymentIntent->id,
                                'paid_at' => now(),
                            ]);

                        return [
                            'success' => true,
                            'order' => $order,
                        ];
                    }
                }
            }

            return [
                'success' => false,
                'error' => 'Payment not successful',
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe payment confirmation failed', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle a Stripe webhook event.
     *
     * @param string $payload
     * @param string $signature
     * @return array
     */
    public function handleWebhook(string $payload, string $signature): array
    {
        $webhookSecret = config('payment.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );

            // Handle different event types
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentSuccess($paymentIntent);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentFailure($paymentIntent);
                    break;

                case 'charge.refunded':
                    $charge = $event->data->object;
                    $this->handleRefund($charge);
                    break;

                default:
                    Log::info('Unhandled webhook event type', ['type' => $event->type]);
            }

            return [
                'success' => true,
                'event_type' => $event->type,
            ];
        } catch (\Exception $e) {
            Log::error('Webhook handling failed', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a refund for an order.
     *
     * @param Order $order
     * @param float|null $amount
     * @return array
     */
    public function createRefund(Order $order, ?float $amount = null): array
    {
        $payment = PaymentModel::where('order_id', $order->id)
            ->where('status', 'completed')
            ->first();

        if (!$payment || !$payment->transaction_id) {
            return [
                'success' => false,
                'error' => 'No successful payment found for this order',
            ];
        }

        try {
            $refundAmount = $amount ? $this->convertToStripeAmount($amount) : null;

            $refund = \Stripe\Refund::create([
                'payment_intent' => $payment->transaction_id,
                'amount' => $refundAmount,
            ]);

            // Update payment record
            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);

            // Update order status
            $order->update([
                'payment_status' => 'refunded',
                'status' => 'cancelled',
            ]);

            return [
                'success' => true,
                'refund' => $refund,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Refund creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Convert amount to Stripe format (cents).
     *
     * @param float $amount
     * @return int
     */
    protected function convertToStripeAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Handle successful payment.
     *
     * @param PaymentIntent $paymentIntent
     * @return void
     */
    protected function handlePaymentSuccess(PaymentIntent $paymentIntent): void
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if ($orderId) {
            $order = Order::find($orderId);

            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                ]);

                PaymentModel::where('order_id', $order->id)
                    ->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                    ]);

                Log::info('Payment succeeded', [
                    'order_id' => $order->id,
                    'payment_intent_id' => $paymentIntent->id,
                ]);
            }
        }
    }

    /**
     * Handle failed payment.
     *
     * @param PaymentIntent $paymentIntent
     * @return void
     */
    protected function handlePaymentFailure(PaymentIntent $paymentIntent): void
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if ($orderId) {
            $order = Order::find($orderId);

            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                ]);

                PaymentModel::where('order_id', $order->id)
                    ->update([
                        'status' => 'failed',
                    ]);

                Log::warning('Payment failed', [
                    'order_id' => $order->id,
                    'payment_intent_id' => $paymentIntent->id,
                    'error' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
                ]);
            }
        }
    }

    /**
     * Handle refund.
     *
     * @param \Stripe\Charge $charge
     * @return void
     */
    protected function handleRefund(\Stripe\Charge $charge): void
    {
        $payment = PaymentModel::where('transaction_id', $charge->payment_intent)
            ->first();

        if ($payment) {
            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);

            $payment->order->update([
                'payment_status' => 'refunded',
                'status' => 'cancelled',
            ]);

            Log::info('Refund processed', [
                'order_id' => $payment->order_id,
                'charge_id' => $charge->id,
            ]);
        }
    }

    /**
     * Save a payment method for a user.
     *
     * @param User $user
     * @param string $paymentMethodId Stripe payment method ID
     * @param bool $setAsDefault
     * @return PaymentMethodModel
     * @throws ApiErrorException
     */
    public function savePaymentMethod(User $user, string $paymentMethodId, bool $setAsDefault = false): PaymentMethodModel
    {
        try {
            // Get or create Stripe customer
            $stripeCustomerId = $this->getOrCreateStripeCustomer($user);

            // Attach payment method to customer
            $stripePaymentMethod = PaymentMethod::retrieve($paymentMethodId);
            $stripePaymentMethod->attach(['customer' => $stripeCustomerId]);

            // If setting as default, unset other defaults
            if ($setAsDefault) {
                PaymentMethodModel::where('user_id', $user->id)
                    ->update(['is_default' => false]);
            }

            // Save payment method to database
            $paymentMethod = PaymentMethodModel::create([
                'user_id' => $user->id,
                'type' => 'card',
                'stripe_payment_method_id' => $paymentMethodId,
                'last_four' => $stripePaymentMethod->card->last4,
                'brand' => $stripePaymentMethod->card->brand,
                'exp_month' => $stripePaymentMethod->card->exp_month,
                'exp_year' => $stripePaymentMethod->card->exp_year,
                'cardholder_name' => $stripePaymentMethod->billing_details->name,
                'is_default' => $setAsDefault,
            ]);

            Log::info('Payment method saved', [
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethodId,
                'is_default' => $setAsDefault,
            ]);

            return $paymentMethod;

        } catch (ApiErrorException $e) {
            Log::error('Failed to save payment method', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a saved payment method.
     *
     * @param PaymentMethodModel $paymentMethod
     * @return bool
     * @throws ApiErrorException
     */
    public function deletePaymentMethod(PaymentMethodModel $paymentMethod): bool
    {
        try {
            // Detach from Stripe
            if ($paymentMethod->stripe_payment_method_id) {
                $stripePaymentMethod = PaymentMethod::retrieve($paymentMethod->stripe_payment_method_id);
                $stripePaymentMethod->detach();
            }

            // Delete from database
            $paymentMethod->delete();

            Log::info('Payment method deleted', [
                'user_id' => $paymentMethod->user_id,
                'payment_method_id' => $paymentMethod->id,
            ]);

            return true;

        } catch (ApiErrorException $e) {
            Log::error('Failed to delete payment method', [
                'payment_method_id' => $paymentMethod->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Set a payment method as default.
     *
     * @param PaymentMethodModel $paymentMethod
     * @return bool
     */
    public function setDefaultPaymentMethod(PaymentMethodModel $paymentMethod): bool
    {
        // Unset other defaults for this user
        PaymentMethodModel::where('user_id', $paymentMethod->user_id)
            ->where('id', '!=', $paymentMethod->id)
            ->update(['is_default' => false]);

        // Set this one as default
        $paymentMethod->update(['is_default' => true]);

        Log::info('Default payment method updated', [
            'user_id' => $paymentMethod->user_id,
            'payment_method_id' => $paymentMethod->id,
        ]);

        return true;
    }

    /**
     * Create payment intent with saved payment method.
     *
     * @param Order $order
     * @param PaymentMethodModel $paymentMethod
     * @return array
     * @throws ApiErrorException
     */
    public function createPaymentIntentWithSavedMethod(Order $order, PaymentMethodModel $paymentMethod): array
    {
        try {
            $stripeCustomerId = $this->getOrCreateStripeCustomer($order->user);

            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToStripeAmount($order->total),
                'currency' => $this->currency,
                'customer' => $stripeCustomerId,
                'payment_method' => $paymentMethod->stripe_payment_method_id,
                'off_session' => false,
                'confirm' => false,
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_email' => $order->user->email,
                ],
                'description' => 'Order #' . $order->order_number,
            ]);

            PaymentModel::where('order_id', $order->id)
                ->update([
                    'transaction_id' => $paymentIntent->id,
                    'status' => 'pending',
                ]);

            Log::info('Payment intent created with saved method', [
                'order_id' => $order->id,
                'payment_intent_id' => $paymentIntent->id,
                'payment_method_id' => $paymentMethod->id,
            ]);

            return [
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ];

        } catch (ApiErrorException $e) {
            Log::error('Failed to create payment intent with saved method', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get or create Stripe customer for user.
     *
     * @param User $user
     * @return string Stripe customer ID
     * @throws ApiErrorException
     */
    protected function getOrCreateStripeCustomer(User $user): string
    {
        // Check if user already has a Stripe customer ID stored
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        // Create new Stripe customer
        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        // Save Stripe customer ID to user
        $user->update(['stripe_customer_id' => $customer->id]);

        Log::info('Stripe customer created', [
            'user_id' => $user->id,
            'customer_id' => $customer->id,
        ]);

        return $customer->id;
    }
}
