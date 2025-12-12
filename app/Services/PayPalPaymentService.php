<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment as PaymentModel;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;

class PayPalPaymentService
{
    protected PayPalHttpClient $client;
    protected string $currency;

    public function __construct()
    {
        $clientId = config('payment.paypal.client_id');
        $clientSecret = config('payment.paypal.secret');
        $mode = config('payment.paypal.mode', 'sandbox');

        $environment = $mode === 'live'
            ? new ProductionEnvironment($clientId, $clientSecret)
            : new SandboxEnvironment($clientId, $clientSecret);

        $this->client = new PayPalHttpClient($environment);
        $this->currency = strtoupper(config('payment.currency', 'EUR'));
    }

    /**
     * Create a PayPal order.
     *
     * @param Order $order
     * @return array
     */
    public function createOrder(Order $order): array
    {
        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $order->order_number,
                    'description' => 'Order #' . $order->order_number,
                    'amount' => [
                        'currency_code' => $this->currency,
                        'value' => number_format($order->total, 2, '.', ''),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $this->currency,
                                'value' => number_format($order->subtotal, 2, '.', ''),
                            ],
                            'shipping' => [
                                'currency_code' => $this->currency,
                                'value' => number_format($order->shipping_cost, 2, '.', ''),
                            ],
                            'tax_total' => [
                                'currency_code' => $this->currency,
                                'value' => number_format($order->tax, 2, '.', ''),
                            ],
                        ],
                    ],
                    'items' => $this->getOrderItems($order),
                ]],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'return_url' => route('payment.paypal.success'),
                    'cancel_url' => route('payment.paypal.cancel'),
                    'user_action' => 'PAY_NOW',
                ],
            ];

            $response = $this->client->execute($request);

            // Update payment record
            PaymentModel::where('order_id', $order->id)->update([
                'transaction_id' => $response->result->id,
                'status' => 'pending',
            ]);

            Log::info('PayPal order created', [
                'order_id' => $order->id,
                'paypal_order_id' => $response->result->id,
            ]);

            // Get approval URL
            $approvalUrl = null;
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    $approvalUrl = $link->href;
                    break;
                }
            }

            return [
                'orderId' => $response->result->id,
                'approvalUrl' => $approvalUrl,
                'status' => $response->result->status,
            ];

        } catch (\Exception $e) {
            Log::error('PayPal order creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Capture (complete) a PayPal order.
     *
     * @param string $paypalOrderId
     * @return array
     */
    public function captureOrder(string $paypalOrderId): array
    {
        try {
            $request = new OrdersCaptureRequest($paypalOrderId);
            $response = $this->client->execute($request);

            $capture = $response->result->purchase_units[0]->payments->captures[0];
            $orderId = $response->result->purchase_units[0]->reference_id;

            // Update payment record
            $payment = PaymentModel::where('transaction_id', $paypalOrderId)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'metadata' => json_encode([
                        'capture_id' => $capture->id,
                        'payer_email' => $response->result->payer->email_address ?? null,
                    ]),
                ]);

                $payment->order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                ]);

                Log::info('PayPal payment captured', [
                    'order_id' => $payment->order_id,
                    'paypal_order_id' => $paypalOrderId,
                    'capture_id' => $capture->id,
                ]);
            }

            return [
                'captureId' => $capture->id,
                'status' => $capture->status,
                'amount' => $capture->amount->value,
            ];

        } catch (\Exception $e) {
            Log::error('PayPal capture failed', [
                'paypal_order_id' => $paypalOrderId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get PayPal order details.
     *
     * @param string $paypalOrderId
     * @return object
     */
    public function getOrder(string $paypalOrderId): object
    {
        try {
            $request = new OrdersGetRequest($paypalOrderId);
            $response = $this->client->execute($request);

            return $response->result;

        } catch (\Exception $e) {
            Log::error('Failed to get PayPal order', [
                'paypal_order_id' => $paypalOrderId,
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
     */
    public function createRefund(Order $order, ?float $amount = null): array
    {
        try {
            $payment = PaymentModel::where('order_id', $order->id)
                ->where('status', 'completed')
                ->first();

            if (!$payment || !$payment->metadata) {
                throw new \Exception('No completed payment found for this order');
            }

            $metadata = json_decode($payment->metadata, true);
            $captureId = $metadata['capture_id'] ?? null;

            if (!$captureId) {
                throw new \Exception('No capture ID found in payment metadata');
            }

            $request = new CapturesRefundRequest($captureId);
            $request->body = [];

            if ($amount !== null) {
                $request->body = [
                    'amount' => [
                        'currency_code' => $this->currency,
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ];
            }

            $response = $this->client->execute($request);

            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);

            $order->update([
                'payment_status' => 'refunded',
                'status' => 'cancelled',
            ]);

            Log::info('PayPal refund created', [
                'order_id' => $order->id,
                'refund_id' => $response->result->id,
                'amount' => $amount ?? $order->total,
            ]);

            return [
                'refund_id' => $response->result->id,
                'amount' => $response->result->amount->value,
                'currency' => $response->result->amount->currency_code,
                'status' => $response->result->status,
            ];

        } catch (\Exception $e) {
            Log::error('PayPal refund failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get order items for PayPal.
     *
     * @param Order $order
     * @return array
     */
    protected function getOrderItems(Order $order): array
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->car->title,
                'description' => $item->car->year . ' ' . $item->car->brand->name,
                'sku' => 'CAR-' . $item->car->id,
                'unit_amount' => [
                    'currency_code' => $this->currency,
                    'value' => number_format($item->price, 2, '.', ''),
                ],
                'quantity' => $item->quantity,
            ];
        }

        return $items;
    }
}
