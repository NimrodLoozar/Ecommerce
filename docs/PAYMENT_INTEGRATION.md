# Payment Integration Guide

## Overview

This Laravel ecommerce application integrates with **three payment gateways**:

-   **Stripe** (primary): Credit card payments, full featured
-   **Mollie** (secondary): EU-focused, supports iDEAL, Bancontact, SOFORT, and more
-   **PayPal** (optional): PayPal accounts and credit cards

The system also supports offline payment methods including bank transfers and cash on delivery.

## Architecture

### Components

1. **PaymentService** (`app/Services/PaymentService.php`)

    - Core service for Stripe payment operations
    - Handles payment intent creation, confirmation, webhooks, refunds, and saved payment methods

2. **MolliePaymentService** (`app/Services/MolliePaymentService.php`)

    - Mollie payment gateway integration
    - Handles payment creation, status checks, webhooks, and refunds
    - Supports iDEAL, credit cards, Bancontact, SOFORT, Giropay

3. **PayPalPaymentService** (`app/Services/PayPalPaymentService.php`)

    - PayPal payment gateway integration
    - Handles order creation, capturing, and refunds
    - Supports PayPal accounts and credit cards

4. **PaymentController** (`app/Http/Controllers/PaymentController.php`)

    - API endpoints for all payment gateways
    - Webhook handlers for Stripe and Mollie
    - Return URL handlers for PayPal

5. **Payment Model** (`app/Models/Payment.php`)

    - Database record of payment transactions
    - Stores transaction IDs, amounts, status, method, and gateway
    - Supports metadata for gateway-specific data

6. **CheckoutController** (`app/Http/Controllers/CheckoutController.php`)
    - Order creation and payment processing
    - Integration with all payment services

### Payment Flow

```
1. User fills checkout form with shipping/billing info
2. User selects payment method (card/bank_transfer/cash)

If payment method is "card":
3. Frontend creates payment intent via AJAX (POST /payment/intent)
4. Backend creates Stripe PaymentIntent, returns client_secret
5. Frontend confirms payment with Stripe.js (confirmCardPayment)
6. Stripe processes payment, returns success/failure
7. Frontend confirms payment on backend (POST /payment/confirm)
8. Backend updates order and payment status
9. Form is submitted with payment_intent_id
10. CheckoutController creates order and finalizes payment

If payment method is "bank_transfer" or "cash":
3. Form submits directly without Stripe processing
4. Order created with pending payment status
5. Payment confirmed manually by admin later
```

## Setup Instructions

### 1. Install Stripe PHP SDK

```bash
composer require stripe/stripe-php
```

### 2. Configure Environment Variables

Add the following to your `.env` file:

```env
PAYMENT_GATEWAY=stripe
PAYMENT_CURRENCY=eur
TAX_RATE=0.21

STRIPE_PUBLIC_KEY=pk_test_your_public_key_here
STRIPE_SECRET_KEY=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here
```

**Get Stripe Keys:**

1. Create account at [stripe.com](https://stripe.com)
2. Go to Dashboard → Developers → API keys
3. Copy "Publishable key" → `STRIPE_PUBLIC_KEY`
4. Copy "Secret key" → `STRIPE_SECRET_KEY`
5. For webhooks: Developers → Webhooks → Add endpoint

### 3. Configure Webhook Endpoint

1. In Stripe Dashboard: Developers → Webhooks → Add endpoint
2. Set endpoint URL: `https://yourdomain.com/webhook/stripe`
3. Select events to listen for:
    - `payment_intent.succeeded`
    - `payment_intent.payment_failed`
    - `charge.refunded`
4. Copy "Signing secret" → `STRIPE_WEBHOOK_SECRET` in .env

### 4. Test with Stripe Test Cards

Stripe provides test card numbers for development:

| Card Number         | Scenario           |
| ------------------- | ------------------ |
| 4242 4242 4242 4242 | Success            |
| 4000 0000 0000 9995 | Declined           |
| 4000 0000 0000 0002 | Declined (generic) |
| 4000 0025 0000 3155 | Requires auth      |

-   Any future expiry date (e.g., 12/25)
-   Any 3-digit CVC
-   Any postal code

---

## Mollie Setup

### 1. Install Mollie PHP SDK

```bash
composer require mollie/mollie-api-php
```

### 2. Configure Environment Variables

Add to `.env`:

```env
MOLLIE_API_KEY=test_your_api_key_here
```

**Get Mollie API Key:**

1. Create account at [mollie.com](https://www.mollie.com)
2. Go to Dashboard → Developers → API keys
3. Copy test API key for development
4. Copy live API key for production

### 3. Configure Webhook Endpoint

1. In Mollie Dashboard: Settings → Webhooks
2. Set webhook URL: `https://yourdomain.com/webhook/mollie`
3. Mollie will send POST requests for all payment status changes

### 4. Test with Mollie Test Mode

When using test API key, Mollie provides test payment methods:

-   **iDEAL**: Select any test bank, payment will succeed automatically
-   **Credit Card**: Use test card `3782 822463 10005` (American Express)
-   **Bancontact**: Test mode always succeeds

**Supported Payment Methods:**

-   iDEAL (Netherlands)
-   Credit cards (Visa, Mastercard, Amex)
-   Bancontact (Belgium)
-   SOFORT (Germany, Austria)
-   Giropay (Germany)
-   EPS (Austria)
-   And many more...

---

## PayPal Setup

### 1. Install PayPal SDK

```bash
composer require paypal/paypal-checkout-sdk
```

**Note:** This SDK is deprecated. Consider upgrading to `paypal/paypal-server-sdk` in the future.

### 2. Configure Environment Variables

Add to `.env`:

```env
PAYPAL_MODE=sandbox  # or 'live' for production
PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id_here
PAYPAL_SANDBOX_CLIENT_SECRET=your_sandbox_client_secret_here
PAYPAL_LIVE_CLIENT_ID=your_live_client_id_here
PAYPAL_LIVE_CLIENT_SECRET=your_live_client_secret_here
```

**Get PayPal Credentials:**

1. Create developer account at [developer.paypal.com](https://developer.paypal.com)
2. Go to Dashboard → My Apps & Credentials
3. Create app in Sandbox/Live section
4. Copy Client ID and Secret

### 3. Configure Return URLs

PayPal requires return URLs for success and cancel:

-   Success: `https://yourdomain.com/payment/paypal/success`
-   Cancel: `https://yourdomain.com/payment/paypal/cancel`

These are automatically configured in the application.

### 4. Test with PayPal Sandbox

When using sandbox mode, use test accounts:

**Personal Account (Buyer):**

-   Email: Personal sandbox account email from dashboard
-   Password: Generated password

**Business Account (Seller):**

-   Email: Business sandbox account email from dashboard
-   Password: Generated password

You can also test credit card payments directly without logging into PayPal.

## API Endpoints

### 1. Create Payment Intent

**POST** `/payment/intent`

Creates a Stripe PaymentIntent for the given amount.

**Headers:**

```
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "amount": 25000.5
}
```

**Response (200 OK):**

```json
{
    "clientSecret": "pi_xxx_secret_xxx"
}
```

**Response (400 Bad Request):**

```json
{
    "message": "Amount is required and must be greater than 0"
}
```

### 2. Confirm Payment

**POST** `/payment/confirm`

Confirms a payment was successful after Stripe processing.

**Headers:**

```
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "payment_intent_id": "pi_xxxxxxxxxxxxx"
}
```

**Response (200 OK):**

```json
{
    "message": "Payment confirmed successfully",
    "payment_intent_id": "pi_xxxxxxxxxxxxx"
}
```

**Response (404 Not Found):**

```json
{
    "message": "Payment intent not found"
}
```

### 3. Webhook Handler

**POST** `/webhook/stripe`

Handles Stripe webhook events (public endpoint, no auth required).

**Headers:**

```
Stripe-Signature: t=xxx,v1=xxx
```

**Request Body:** (Stripe event payload)

**Response (200 OK):**

```json
{
    "message": "Webhook handled"
}
```

### 4. Create Mollie Payment

**POST** `/payment/mollie/create`

Creates a Mollie payment and returns checkout URL.

**Headers:**

```
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "order_id": 123,
    "method": "ideal" // Optional: ideal, creditcard, bancontact, etc.
}
```

**Response (200 OK):**

```json
{
    "success": true,
    "checkoutUrl": "https://www.mollie.com/checkout/...",
    "paymentId": "tr_xxxxxxxxxxxxx"
}
```

### 5. Mollie Webhook Handler

**POST** `/webhook/mollie`

Handles Mollie webhook events (public endpoint, no auth required).

**Request Body:**

```json
{
    "id": "tr_xxxxxxxxxxxxx"
}
```

**Response (200 OK):**

```json
{
    "received": true
}
```

### 6. Create PayPal Order

**POST** `/payment/paypal/create`

Creates a PayPal order and returns approval URL.

**Headers:**

```
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "order_id": 123
}
```

**Response (200 OK):**

```json
{
    "success": true,
    "orderId": "PAYPAL-ORDER-ID",
    "approvalUrl": "https://www.paypal.com/checkoutnow?token=..."
}
```

### 7. Capture PayPal Order

**POST** `/payment/paypal/capture`

Captures a PayPal order after customer approval.

**Headers:**

```
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "order_id": "PAYPAL-ORDER-ID"
}
```

**Response (200 OK):**

```json
{
    "success": true,
    "captureId": "CAPTURE-ID",
    "status": "COMPLETED"
}
```

### 8. PayPal Success Return

**GET** `/payment/paypal/success?token={order_id}`

Handles PayPal success redirect (public endpoint, no auth required).

Redirects to order confirmation page or cart with appropriate message.

### 9. PayPal Cancel Return

**GET** `/payment/paypal/cancel?token={order_id}`

Handles PayPal cancellation redirect (public endpoint, no auth required).

Redirects to checkout page with error message.

### 10. Create Refund

**POST** `/orders/{order}/refund`

Issues a full or partial refund for an order (works with all gateways).

**Headers:**

```
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "amount": 100.0 // Optional: omit for full refund
}
```

**Response (200 OK):**

```json
{
    "message": "Refund processed successfully",
    "refund_id": "re_xxxxxxxxxxxxx"
}
```

## Frontend Integration

### Multi-Gateway Checkout Page

The checkout page (`resources/views/checkout/index.blade.php`) supports three payment gateways with a unified interface.

#### Payment Gateway Selection

Users select their preferred gateway via radio buttons:

```html
<input type="radio" name="payment_gateway" value="stripe" checked />
<input type="radio" name="payment_gateway" value="mollie" />
<input type="radio" name="payment_gateway" value="paypal" />
```

#### 1. Stripe Integration with Elements

For Stripe, we use Stripe Elements for secure card input:

```javascript
// Initialize Stripe with public key
const stripe = Stripe('{{ config('payment.stripe.public_key') }}');
const elements = stripe.elements();
const cardElement = elements.create('card');
cardElement.mount('#card-element');

// Handle form submission
form.addEventListener('submit', async (event) => {
    event.preventDefault();

    // Create payment intent
    const response = await fetch('/payment/intent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ amount: totalAmount })
    });

    const { clientSecret } = await response.json();

    // Confirm payment with Stripe
    const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
        payment_method: { card: cardElement }
    });

    if (error) {
        // Show error to user
    } else if (paymentIntent.status === 'succeeded') {
        // Confirm on backend
        await fetch('/payment/confirm', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ payment_intent_id: paymentIntent.id })
        });

        // Submit checkout form
        form.submit();
    }
}
```

#### 2. Mollie Integration (Redirect Flow)

For Mollie, we create the order first, then redirect to Mollie's checkout:

```javascript
async function handleMolliePayment() {
    // Step 1: Create order on your server
    const formData = new FormData(form);
    const orderResponse = await fetch("/checkout", {
        method: "POST",
        body: formData,
    });
    const orderData = await orderResponse.json();

    // Step 2: Create Mollie payment
    const mollieMethod = document.querySelector(
        'select[name="mollie_method"]'
    ).value;
    const mollieResponse = await fetch("/payment/mollie/create", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            order_id: orderData.order_id,
            method: mollieMethod || null, // Optional: preselect payment method
        }),
    });
    const mollieData = await mollieResponse.json();

    // Step 3: Redirect to Mollie checkout
    window.location.href = mollieData.checkoutUrl;
}
```

After payment, Mollie sends webhook to `/webhook/mollie` to update order status.

#### 3. PayPal Integration (Redirect Flow)

For PayPal, similar to Mollie:

```javascript
async function handlePayPalPayment() {
    // Step 1: Create order on your server
    const formData = new FormData(form);
    const orderResponse = await fetch("/checkout", {
        method: "POST",
        body: formData,
    });
    const orderData = await orderResponse.json();

    // Step 2: Create PayPal order
    const paypalResponse = await fetch("/payment/paypal/create", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            order_id: orderData.order_id,
        }),
    });
    const paypalData = await paypalResponse.json();

    // Step 3: Redirect to PayPal for approval
    window.location.href = paypalData.approvalUrl;
}
```

After approval, PayPal redirects to `/payment/paypal/success` which captures the payment.

### Key Points

1. **Stripe**: Card details stay in browser, processed by Stripe.js
2. **Mollie**: Redirect to Mollie for payment, webhook confirms status
3. **PayPal**: Redirect to PayPal for approval, return URL captures payment
4. **Validate on backend**: Always verify payment status server-side
5. **Use HTTPS in production**: Required for PCI compliance and secure redirects
6. **Handle errors gracefully**: Show user-friendly error messages for all gateways

## PaymentService Methods

### createPaymentIntent(Order $order): array

Creates a Stripe PaymentIntent for an order.

**Parameters:**

-   `$order` (Order): The order to create payment for

**Returns:**

```php
[
    'clientSecret' => 'pi_xxx_secret_xxx',
    'paymentIntentId' => 'pi_xxxxxxxxxxxxx'
]
```

**Example:**

```php
use App\Services\PaymentService;

$paymentService = app(PaymentService::class);
$result = $paymentService->createPaymentIntent($order);

return response()->json([
    'clientSecret' => $result['clientSecret']
]);
```

### confirmPayment(string $paymentIntentId): bool

Confirms a payment was successful and updates the order.

**Parameters:**

-   `$paymentIntentId` (string): The Stripe PaymentIntent ID

**Returns:** `bool` - true if successful, false otherwise

**Example:**

```php
$success = $paymentService->confirmPayment($request->payment_intent_id);

if ($success) {
    return response()->json(['message' => 'Payment confirmed']);
}
```

### handleWebhook(string $payload, string $signature): void

Processes Stripe webhook events.

**Parameters:**

-   `$payload` (string): Raw webhook payload
-   `$signature` (string): Stripe signature header

**Events Handled:**

-   `payment_intent.succeeded` - Updates order to paid
-   `payment_intent.payment_failed` - Updates order to failed
-   `charge.refunded` - Records refund

**Example:**

```php
$payload = @file_get_contents('php://input');
$signature = $request->header('Stripe-Signature');

$paymentService->handleWebhook($payload, $signature);
```

### createRefund(Order $order, ?float $amount = null): array

Issues a full or partial refund.

**Parameters:**

-   `$order` (Order): The order to refund
-   `$amount` (float|null): Refund amount (null for full refund)

**Returns:**

```php
[
    'refund_id' => 're_xxxxxxxxxxxxx',
    'amount' => 250.50,
    'currency' => 'eur',
    'status' => 'succeeded'
]
```

**Example:**

```php
// Full refund
$refund = $paymentService->createRefund($order);

// Partial refund (€100)
$refund = $paymentService->createRefund($order, 100.00);
```

## Database Schema

### payments table

```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    transaction_id VARCHAR(255) UNIQUE,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('card', 'bank_transfer', 'cash') NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    metadata TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

### Payment Status Values

| Status      | Description                         |
| ----------- | ----------------------------------- |
| `pending`   | Payment initiated but not completed |
| `completed` | Payment successful                  |
| `failed`    | Payment attempt failed              |
| `refunded`  | Payment was refunded (full/partial) |

## Error Handling

### Common Errors

1. **Invalid API Key**

    ```
    Error: No such API key
    Solution: Check STRIPE_SECRET_KEY in .env
    ```

2. **Invalid Webhook Signature**

    ```
    Error: Webhook signature verification failed
    Solution: Check STRIPE_WEBHOOK_SECRET in .env
    ```

3. **Card Declined**

    ```
    Error: Your card was declined
    Solution: User needs to use different card
    ```

4. **Insufficient Funds**
    ```
    Error: Your card has insufficient funds
    Solution: User needs to add funds or use different card
    ```

### Logging

All payment operations are logged:

```php
Log::info('Payment intent created', [
    'order_id' => $order->id,
    'amount' => $amount,
    'payment_intent_id' => $paymentIntent->id
]);

Log::error('Payment confirmation failed', [
    'payment_intent_id' => $paymentIntentId,
    'error' => $e->getMessage()
]);
```

Check logs: `storage/logs/laravel.log`

## Testing

### Manual Testing Checklist

-   [ ] Create payment intent with valid amount
-   [ ] Confirm payment with valid PaymentIntent ID
-   [ ] Test successful card payment (4242 4242 4242 4242)
-   [ ] Test declined card (4000 0000 0000 9995)
-   [ ] Test webhook events (use Stripe CLI)
-   [ ] Test full refund
-   [ ] Test partial refund
-   [ ] Test bank transfer order creation
-   [ ] Test cash on delivery order creation

### Automated Tests

Create feature tests for payment flows:

```php
// tests/Feature/PaymentTest.php
public function test_user_can_create_payment_intent()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/payment/intent', [
            'amount' => 100.50
        ]);

    $response->assertOk()
        ->assertJsonStructure(['clientSecret']);
}

public function test_webhook_updates_order_status()
{
    // Mock Stripe webhook event
    $payload = json_encode([
        'type' => 'payment_intent.succeeded',
        'data' => [
            'object' => [
                'id' => 'pi_test123',
                'metadata' => ['order_id' => '1']
            ]
        ]
    ]);

    $response = $this->postJson('/webhook/stripe', [], [
        'Stripe-Signature' => $this->generateSignature($payload)
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('orders', [
        'id' => 1,
        'payment_status' => 'paid'
    ]);
}
```

### Stripe CLI Testing

Install Stripe CLI for webhook testing:

```bash
# Install Stripe CLI
# https://stripe.com/docs/stripe-cli

# Login to Stripe
stripe login

# Forward webhooks to local server
stripe listen --forward-to localhost:8000/webhook/stripe

# Trigger test events
stripe trigger payment_intent.succeeded
stripe trigger payment_intent.payment_failed
stripe trigger charge.refunded
```

## Security Best Practices

1. **Never log sensitive data**

    - Don't log card numbers, CVV, or full API keys
    - Use `Log::info('Payment processed', ['order_id' => $id])` instead

2. **Validate webhook signatures**

    - Always verify Stripe-Signature header
    - Use `Webhook::constructEvent()` to validate

3. **Use HTTPS in production**

    - Required for PCI compliance
    - Stripe.js won't work over HTTP in production

4. **Store minimal payment data**

    - Don't store card numbers or CVV
    - Only store transaction IDs and payment status

5. **Implement idempotency**

    - Prevent duplicate payments on form resubmits
    - Check if payment already exists before creating

6. **Rate limit payment endpoints**
    - Prevent brute force attacks
    - Use Laravel's throttle middleware

## Production Deployment

### Pre-Deployment Checklist

-   [ ] Switch from test keys to live Stripe keys
-   [ ] Update webhook URL to production domain
-   [ ] Enable HTTPS/SSL certificate
-   [ ] Set up monitoring for failed payments
-   [ ] Configure email notifications for refunds
-   [ ] Test webhook delivery in production
-   [ ] Set up Stripe Radar for fraud prevention
-   [ ] Enable 3D Secure authentication
-   [ ] Configure currency and tax rates correctly

### Environment Variables (Production)

```env
PAYMENT_GATEWAY=stripe
PAYMENT_CURRENCY=eur
TAX_RATE=0.21

STRIPE_PUBLIC_KEY=pk_live_xxxxxxxxxxxx
STRIPE_SECRET_KEY=sk_live_xxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxx
```

### Monitoring

Monitor these metrics:

1. **Payment Success Rate** - Track successful vs failed payments
2. **Average Payment Time** - Monitor payment processing speed
3. **Refund Rate** - Track refund requests
4. **Webhook Delivery** - Ensure webhooks are received
5. **API Errors** - Monitor Stripe API errors in logs

### Support

-   **Stripe Dashboard**: [dashboard.stripe.com](https://dashboard.stripe.com)
-   **Stripe Docs**: [stripe.com/docs](https://stripe.com/docs)
-   **Stripe Support**: [support.stripe.com](https://support.stripe.com)

## Troubleshooting

### Payment Intent Not Created

**Symptom:** 400 Bad Request when creating payment intent

**Solutions:**

1. Check if amount is valid and greater than 0
2. Verify Stripe API key in `.env`
3. Check Laravel logs for detailed error

### Webhook Not Received

**Symptom:** Stripe shows webhook sent but order not updated

**Solutions:**

1. Verify webhook URL is correct and accessible
2. Check `STRIPE_WEBHOOK_SECRET` matches Stripe Dashboard
3. Check Laravel logs for webhook errors
4. Test locally with Stripe CLI

### Card Payment Fails

**Symptom:** Payment fails with "Card declined" error

**Solutions:**

1. Use Stripe test cards in development
2. Check if Stripe account is active
3. Verify card has sufficient funds (in production)
4. Check if 3D Secure authentication is required

### Refund Not Processed

**Symptom:** Refund fails with error

**Solutions:**

1. Verify payment was captured (not just authorized)
2. Check if refund amount exceeds original payment
3. Ensure order has a valid transaction_id
4. Check Stripe Dashboard for refund status

## Additional Resources

-   [Stripe PHP SDK Documentation](https://stripe.com/docs/api?lang=php)
-   [Stripe Elements Guide](https://stripe.com/docs/payments/elements)
-   [Stripe Webhooks Guide](https://stripe.com/docs/webhooks)
-   [PCI Compliance](https://stripe.com/docs/security/guide)
-   [Testing Stripe](https://stripe.com/docs/testing)
