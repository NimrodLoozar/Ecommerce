# Payment Integration - Quick Reference

## Quick Start

### 1. Environment Setup (5 minutes)

```bash
# Install Stripe SDK
composer require stripe/stripe-php

# Add to .env
STRIPE_PUBLIC_KEY=pk_test_51xxxxx
STRIPE_SECRET_KEY=sk_test_51xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx
```

### 2. Test a Payment (2 minutes)

1. Go to checkout page: `http://localhost:8000/checkout`
2. Select "Credit card" payment method
3. Enter test card: `4242 4242 4242 4242`
4. Expiry: Any future date (e.g., `12/25`)
5. CVC: Any 3 digits (e.g., `123`)
6. Click "Confirm order"

### 3. Verify Payment (1 minute)

```bash
# Check order payment status
php artisan tinker
>>> Order::latest()->first()->payment_status
=> "paid"

# Check payment record
>>> Payment::latest()->first()
```

## Common Tasks

### Create Payment Intent

```php
use App\Services\PaymentService;

$paymentService = app(PaymentService::class);
$result = $paymentService->createPaymentIntent($order);

// Returns: ['clientSecret' => '...', 'paymentIntentId' => '...']
```

### Confirm Payment

```php
$success = $paymentService->confirmPayment($paymentIntentId);

if ($success) {
    // Payment confirmed, order updated to "paid"
}
```

### Issue Refund

```php
// Full refund
$refund = $paymentService->createRefund($order);

// Partial refund (€50)
$refund = $paymentService->createRefund($order, 50.00);
```

### Check Payment Status

```php
$order = Order::find($orderId);

if ($order->payment_status === 'paid') {
    // Payment successful
} elseif ($order->payment_status === 'pending') {
    // Awaiting payment
} elseif ($order->payment_status === 'failed') {
    // Payment failed
}
```

## Frontend Integration

### Stripe Elements (Minimal Setup)

```html
<!-- Add Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<!-- Card input container -->
<div id="card-element"></div>
<div id="card-errors" role="alert"></div>

<script>
    // Initialize Stripe
    const stripe = Stripe("pk_test_YOUR_KEY");
    const elements = stripe.elements();
    const cardElement = elements.create("card");
    cardElement.mount("#card-element");

    // Handle form submission
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Create payment intent
        const res = await fetch("/payment/intent", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({ amount: 100.5 }),
        });

        const { clientSecret } = await res.json();

        // Confirm payment
        const { error, paymentIntent } = await stripe.confirmCardPayment(
            clientSecret,
            {
                payment_method: { card: cardElement },
            }
        );

        if (error) {
            document.getElementById("card-errors").textContent = error.message;
        } else {
            // Confirm on backend
            await fetch("/payment/confirm", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify({ payment_intent_id: paymentIntent.id }),
            });

            // Submit form
            form.submit();
        }
    });
</script>
```

## Stripe Test Cards

| Card Number         | Scenario             |
| ------------------- | -------------------- |
| 4242 4242 4242 4242 | ✅ Success           |
| 4000 0000 0000 9995 | ❌ Declined          |
| 4000 0025 0000 3155 | 🔐 Requires 3DS auth |
| 4000 0000 0000 0002 | ❌ Generic decline   |

-   **Expiry:** Any future date (e.g., 12/25)
-   **CVC:** Any 3 digits (e.g., 123)
-   **Postal Code:** Any valid code

## Webhook Testing

### Local Testing with Stripe CLI

```bash
# Install Stripe CLI
# https://stripe.com/docs/stripe-cli

# Login
stripe login

# Forward webhooks to local server
stripe listen --forward-to localhost:8000/webhook/stripe

# Copy webhook signing secret and add to .env
STRIPE_WEBHOOK_SECRET=whsec_xxxxx

# Trigger test events
stripe trigger payment_intent.succeeded
stripe trigger payment_intent.payment_failed
stripe trigger charge.refunded
```

### Manual Webhook Testing

1. Go to Stripe Dashboard → Developers → Webhooks
2. Select your webhook endpoint
3. Click "Send test webhook"
4. Choose event type (e.g., `payment_intent.succeeded`)
5. Click "Send test webhook"

## API Endpoints Cheat Sheet

| Endpoint              | Method | Auth | Description             |
| --------------------- | ------ | ---- | ----------------------- |
| `/payment/intent`     | POST   | ✅   | Create payment intent   |
| `/payment/confirm`    | POST   | ✅   | Confirm payment success |
| `/webhook/stripe`     | POST   | ❌   | Stripe webhook handler  |
| `/orders/{id}/refund` | POST   | ✅   | Issue refund            |

## Debugging

### Enable Stripe Logs

```php
// In PaymentService methods, add:
Log::info('Payment intent created', [
    'order_id' => $order->id,
    'amount' => $amount,
    'payment_intent_id' => $paymentIntent->id
]);
```

### Check Laravel Logs

```bash
# View latest logs
tail -f storage/logs/laravel.log

# View payment-related logs only
tail -f storage/logs/laravel.log | grep -i payment
```

### Check Stripe Dashboard

1. Go to [dashboard.stripe.com](https://dashboard.stripe.com)
2. Click "Payments" to see all transactions
3. Click "Logs" → "Webhooks" to see webhook deliveries
4. Click on any event to see payload and response

### Common Errors

**"No such payment_intent"**

-   Payment intent ID is invalid or expired
-   Check if payment was already captured

**"Invalid API Key"**

-   Check `STRIPE_SECRET_KEY` in `.env`
-   Ensure you're using test key in development

**"Webhook signature verification failed"**

-   Check `STRIPE_WEBHOOK_SECRET` in `.env`
-   Verify webhook endpoint URL is correct

**"Your card was declined"**

-   Use valid test card number (4242 4242 4242 4242)
-   Check Stripe account status

## Payment Flow Diagram

```
┌─────────────┐
│   Customer  │
│ fills form  │
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│ Selects "Credit     │
│ card" payment       │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ Enters card details │
│ (Stripe Elements)   │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ Clicks "Confirm     │
│ order" button       │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ JS: Create payment  │
│ intent (AJAX)       │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ Backend: Create     │
│ Stripe PaymentIntent│
└──────┬──────────────┘
       │
       ▼ (clientSecret)
┌─────────────────────┐
│ JS: Confirm payment │
│ with Stripe.js      │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ Stripe: Process     │
│ card payment        │
└──────┬──────────────┘
       │
       ▼ (success/failure)
┌─────────────────────┐
│ JS: Confirm on      │
│ backend (AJAX)      │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ Backend: Update     │
│ order status        │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ JS: Submit form to  │
│ create order        │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ CheckoutController: │
│ Finalize order      │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ Redirect to order   │
│ confirmation page   │
└─────────────────────┘
```

## Quick Troubleshooting

| Issue                         | Solution                                         |
| ----------------------------- | ------------------------------------------------ |
| Payment intent creation fails | Check Stripe API key in `.env`                   |
| Card payment fails            | Use test card 4242 4242 4242 4242                |
| Webhook not received          | Verify webhook URL and secret                    |
| Order status not updated      | Check webhook signature verification             |
| Refund fails                  | Verify payment was captured, not just authorized |
| CORS error on API call        | Add CSRF token to AJAX requests                  |

## Production Checklist

-   [ ] Switch to live Stripe keys (pk_live_xxx, sk_live_xxx)
-   [ ] Update webhook URL to production domain
-   [ ] Enable HTTPS/SSL certificate
-   [ ] Test webhook delivery in production
-   [ ] Enable Stripe Radar for fraud detection
-   [ ] Configure email notifications for refunds
-   [ ] Set up monitoring for failed payments
-   [ ] Test with real credit card (small amount)
-   [ ] Document support procedures for payment issues

## Support Resources

-   **Full Documentation:** `docs/PAYMENT_INTEGRATION.md`
-   **Stripe Dashboard:** [dashboard.stripe.com](https://dashboard.stripe.com)
-   **Stripe API Docs:** [stripe.com/docs/api](https://stripe.com/docs/api)
-   **Stripe Support:** [support.stripe.com](https://support.stripe.com)
-   **Test Cards:** [stripe.com/docs/testing](https://stripe.com/docs/testing)

## Example: Admin Refund Interface

```php
// routes/web.php
Route::post('/admin/orders/{order}/refund', [Admin\OrderController::class, 'refund'])
    ->middleware(['auth', 'admin']);

// Admin\OrderController.php
public function refund(Order $order, Request $request)
{
    $request->validate([
        'amount' => 'nullable|numeric|min:0|max:' . $order->total,
    ]);

    $paymentService = app(PaymentService::class);

    try {
        $refund = $paymentService->createRefund(
            $order,
            $request->input('amount') // null for full refund
        );

        return back()->with('success', 'Refund processed successfully');
    } catch (\Exception $e) {
        return back()->with('error', 'Refund failed: ' . $e->getMessage());
    }
}
```

```blade
<!-- admin/orders/show.blade.php -->
<form action="{{ route('admin.orders.refund', $order) }}" method="POST">
    @csrf

    <label>Refund Amount (leave empty for full refund)</label>
    <input type="number" name="amount" step="0.01" max="{{ $order->total }}"
           placeholder="Full refund: €{{ $order->total }}">

    <button type="submit">Process Refund</button>
</form>
```

---

**Last Updated:** December 2025  
**Version:** 1.0.0  
**Stripe SDK:** v19.0.0
