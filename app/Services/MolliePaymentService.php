<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment as PaymentModel;
use Illuminate\Support\Facades\Log;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Exceptions\ApiException;

class MolliePaymentService
{
    protected MollieApiClient $mollie;
    protected string $currency;

    public function __construct()
    {
        $this->mollie = new MollieApiClient();
        $this->mollie->setApiKey(config('payment.mollie.api_key'));
        $this->currency = strtoupper(config('payment.currency', 'EUR'));
    }

    /**
     * Create a Mollie payment for an order.
     *
     * @param Order $order
     * @param string|null $method Specific payment method (ideal, creditcard, etc.)
     * @return array
     * @throws ApiException
     */
    public function createPayment(Order $order, ?string $method = null): array
    {
        try {
            $payment = $this->mollie->payments->create([
                'amount' => [
                    'currency' => $this->currency,
                    'value' => number_format($order->total, 2, '.', ''),
                ],
                'description' => 'Order #' . $order->order_number,
                'redirectUrl' => route('orders.show', $order->id),
                'webhookUrl' => route('webhook.mollie'),
                'method' => $method, // null = all methods
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);

            // Update payment record
            PaymentModel::where('order_id', $order->id)->update([
                'transaction_id' => $payment->id,
                'status' => 'pending',
            ]);

            Log::info('Mollie payment created', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'method' => $method ?? 'all',
            ]);

            return [
                'checkoutUrl' => $payment->getCheckoutUrl(),
                'paymentId' => $payment->id,
                'status' => $payment->status,
            ];

        } catch (ApiException $e) {
            Log::error('Mollie payment creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get payment status.
     *
     * @param string $paymentId
     * @return string
     * @throws ApiException
     */
    public function getPaymentStatus(string $paymentId): string
    {
        try {
            $payment = $this->mollie->payments->get($paymentId);
            return $payment->status;
        } catch (ApiException $e) {
            Log::error('Failed to get Mollie payment status', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle Mollie webhook.
     *
     * @param string $paymentId
     * @return void
     * @throws ApiException
     */
    public function handleWebhook(string $paymentId): void
    {
        try {
            $payment = $this->mollie->payments->get($paymentId);

            $orderId = $payment->metadata->order_id ?? null;

            if (!$orderId) {
                Log::warning('Mollie webhook: No order ID in metadata', [
                    'payment_id' => $paymentId,
                ]);
                return;
            }

            $paymentRecord = PaymentModel::where('order_id', $orderId)
                ->where('transaction_id', $paymentId)
                ->first();

            if (!$paymentRecord) {
                Log::warning('Mollie webhook: Payment record not found', [
                    'payment_id' => $paymentId,
                    'order_id' => $orderId,
                ]);
                return;
            }

            // Handle different payment statuses
            match ($payment->status) {
                'paid' => $this->handlePaymentSuccess($paymentRecord, $payment),
                'failed', 'canceled', 'expired' => $this->handlePaymentFailure($paymentRecord, $payment),
                'refunded' => $this->handlePaymentRefund($paymentRecord, $payment),
                default => Log::info('Mollie payment status: ' . $payment->status, [
                    'payment_id' => $paymentId,
                    'order_id' => $orderId,
                ]),
            };

        } catch (ApiException $e) {
            Log::error('Mollie webhook handling failed', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a refund.
     *
     * @param Order $order
     * @param float|null $amount
     * @return array
     * @throws ApiException
     */
    public function createRefund(Order $order, ?float $amount = null): array
    {
        try {
            $payment = PaymentModel::where('order_id', $order->id)
                ->where('status', 'completed')
                ->first();

            if (!$payment || !$payment->transaction_id) {
                throw new \Exception('No completed payment found for this order');
            }

            $molliePayment = $this->mollie->payments->get($payment->transaction_id);

            $refundData = [
                'description' => 'Refund for order #' . $order->order_number,
            ];

            if ($amount !== null) {
                $refundData['amount'] = [
                    'currency' => $this->currency,
                    'value' => number_format($amount, 2, '.', ''),
                ];
            }

            $refund = $molliePayment->refund($refundData);

            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);

            $order->update([
                'payment_status' => 'refunded',
                'status' => 'cancelled',
            ]);

            Log::info('Mollie refund created', [
                'order_id' => $order->id,
                'refund_id' => $refund->id,
                'amount' => $amount ?? $order->total,
            ]);

            return [
                'refund_id' => $refund->id,
                'amount' => $refund->amount->value,
                'currency' => $refund->amount->currency,
                'status' => $refund->status,
            ];

        } catch (\Exception $e) {
            Log::error('Mollie refund failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get available payment methods.
     *
     * @return array
     * @throws ApiException
     */
    public function getAvailableMethods(): array
    {
        try {
            $methods = $this->mollie->methods->allActive();
            $result = [];

            foreach ($methods as $method) {
                $result[] = [
                    'id' => $method->id,
                    'description' => $method->description,
                    'image' => $method->image->size2x,
                ];
            }

            return $result;

        } catch (ApiException $e) {
            Log::error('Failed to get Mollie payment methods', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle successful payment.
     *
     * @param PaymentModel $payment
     * @param \Mollie\Api\Resources\Payment $molliePayment
     * @return void
     */
    protected function handlePaymentSuccess(PaymentModel $payment, \Mollie\Api\Resources\Payment $molliePayment): void
    {
        $payment->update(['status' => 'completed']);

        $payment->order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        Log::info('Mollie payment succeeded', [
            'order_id' => $payment->order_id,
            'payment_id' => $molliePayment->id,
        ]);
    }

    /**
     * Handle failed payment.
     *
     * @param PaymentModel $payment
     * @param \Mollie\Api\Resources\Payment $molliePayment
     * @return void
     */
    protected function handlePaymentFailure(PaymentModel $payment, \Mollie\Api\Resources\Payment $molliePayment): void
    {
        $payment->update(['status' => 'failed']);

        $payment->order->update([
            'payment_status' => 'failed',
        ]);

        Log::warning('Mollie payment failed', [
            'order_id' => $payment->order_id,
            'payment_id' => $molliePayment->id,
            'status' => $molliePayment->status,
        ]);
    }

    /**
     * Handle payment refund.
     *
     * @param PaymentModel $payment
     * @param \Mollie\Api\Resources\Payment $molliePayment
     * @return void
     */
    protected function handlePaymentRefund(PaymentModel $payment, \Mollie\Api\Resources\Payment $molliePayment): void
    {
        $payment->update([
            'status' => 'refunded',
            'refunded_at' => now(),
        ]);

        $payment->order->update([
            'payment_status' => 'refunded',
            'status' => 'cancelled',
        ]);

        Log::info('Mollie payment refunded', [
            'order_id' => $payment->order_id,
            'payment_id' => $molliePayment->id,
        ]);
    }
}
