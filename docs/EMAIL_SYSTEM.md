# Email Notification System

This document describes the email notification system implemented in the CarHub Platform.

## Overview

The application uses Laravel's queue system to send email notifications asynchronously. All emails are queued to improve application performance and user experience.

## Email Types

### 1. Order-Related Emails (HIGH Priority)

#### Order Confirmation Email

-   **Trigger:** When a new order is created
-   **Recipients:** Customer
-   **Job:** `SendOrderConfirmationEmail`
-   **Template:** `emails.orders.confirmation`
-   **Dispatched from:** `CheckoutController@store`

#### Order Status Update Email

-   **Trigger:** When order status changes
-   **Recipients:** Customer
-   **Job:** `SendOrderStatusUpdateEmail`
-   **Template:** `emails.orders.status-update`
-   **Dispatched from:** `Dealer\OrderController@update`
-   **Statuses:** pending, confirmed, processing, shipped, delivered, completed, cancelled

### 2. Inquiry-Related Emails (MEDIUM Priority)

#### Inquiry Received Email

-   **Trigger:** When a customer submits an inquiry
-   **Recipients:** Dealer (if car has dealer) or Admin (for platform-owned cars)
-   **Job:** `SendInquiryReceivedEmail`
-   **Template:** `emails.inquiries.received`
-   **Dispatched from:** `InquiryController@store` (TODO: implement)

#### Inquiry Response Email

-   **Trigger:** When dealer/admin responds to an inquiry
-   **Recipients:** Customer
-   **Job:** `SendInquiryResponseEmail`
-   **Template:** `emails.inquiries.response`
-   **Dispatched from:** `Dealer\InquiryController@update` (TODO: implement)

### 3. Test Drive Emails (MEDIUM Priority)

#### Test Drive Confirmation Email

-   **Trigger:** When test drive status is set to 'confirmed'
-   **Recipients:** Customer
-   **Job:** `SendTestDriveConfirmationEmail`
-   **Template:** `emails.test-drives.confirmation`
-   **Dispatched from:** `TestDriveController@update` (TODO: implement)

#### Test Drive Reminder Email

-   **Trigger:** Daily at 8 AM for test drives scheduled tomorrow
-   **Recipients:** Customer
-   **Job:** `SendTestDriveReminderEmail`
-   **Template:** `emails.test-drives.reminder`
-   **Scheduled:** Via `SendTestDriveReminders` command

### 4. Trade-In Email (MEDIUM Priority)

#### Trade-In Offer Email

-   **Trigger:** When dealer makes an offer on a trade-in
-   **Recipients:** Customer
-   **Job:** `SendTradeInOfferEmail`
-   **Template:** `emails.trade-ins.offer`
-   **Dispatched from:** `TradeInController@update` (TODO: implement)

### 5. Review Request Email (LOW Priority)

#### Review Request Email

-   **Trigger:** 7 days after order is delivered
-   **Recipients:** Customer
-   **Job:** `SendReviewRequestEmail`
-   **Template:** `emails.reviews.request`
-   **Scheduled:** Via `SendReviewRequests` command

### 6. Dealer-Related Emails (LOW Priority)

#### Dealer Approval Email

-   **Trigger:** When dealer account is approved
-   **Recipients:** Dealer
-   **Job:** `SendDealerApprovalEmail`
-   **Template:** `emails.dealers.approval`
-   **Dispatched from:** `Admin\DealerController@update` (TODO: implement)

#### Commission Payment Email

-   **Trigger:** When commission payment is processed
-   **Recipients:** Dealer
-   **Job:** `SendCommissionPaymentEmail`
-   **Template:** `emails.commissions.payment`
-   **Dispatched from:** `Admin\CommissionController@markAsPaid` (TODO: implement)

## Queue Configuration

### Development Environment

The default queue connection is set to `database` in `.env`:

```env
QUEUE_CONNECTION=database
```

To process queued jobs in development:

```bash
php artisan queue:work
```

Or run with verbose output:

```bash
php artisan queue:work --verbose
```

### Production Environment

For production, it's recommended to use **Redis** for better performance:

1. Update `.env`:

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

2. Use **Supervisor** to keep queue workers running:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
stopwaitsecs=3600
```

## Mail Configuration

### Development (Log Driver)

Emails are logged to `storage/logs/laravel.log` instead of being sent:

```env
MAIL_MAILER=log
```

### Production (SMTP)

Configure a mail service (e.g., Mailgun, SendGrid, SES):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@carhub.com"
MAIL_FROM_NAME="CarHub Platform"
```

## Scheduled Commands

Add these to `app/Console/Kernel.php` schedule method:

```php
protected function schedule(Schedule $schedule): void
{
    // Send test drive reminders daily at 8 AM
    $schedule->command('test-drives:send-reminders')
        ->dailyAt('08:00');

    // Send review requests daily at 10 AM
    $schedule->command('reviews:send-requests')
        ->dailyAt('10:00');
}
```

Run the scheduler in production:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Or test manually:

```bash
php artisan test-drives:send-reminders
php artisan reviews:send-requests
```

## Email Templates

All email templates are located in `resources/views/emails/` and use:

-   Inline CSS for maximum email client compatibility
-   Responsive design (mobile-friendly)
-   Brand colors (indigo/purple gradient)
-   Clear call-to-action buttons

### Template Structure

```
resources/views/emails/
├── orders/
│   ├── confirmation.blade.php
│   └── status-update.blade.php
├── inquiries/
│   ├── received.blade.php
│   └── response.blade.php
├── test-drives/
│   ├── confirmation.blade.php
│   └── reminder.blade.php
├── trade-ins/
│   └── offer.blade.php
├── reviews/
│   └── request.blade.php
├── dealers/
│   └── approval.blade.php
└── commissions/
    └── payment.blade.php
```

## Testing Emails

### Preview in Browser

Use Laravel's mail preview feature:

```php
Route::get('/email-preview', function () {
    $order = Order::with(['user', 'items.car', 'shippingAddress', 'billingAddress', 'payments'])->first();
    return new App\Mail\OrderConfirmationMail($order);
});
```

### Send Test Email

```bash
php artisan tinker
```

```php
use App\Jobs\SendOrderConfirmationEmail;
use App\Models\Order;

$order = Order::with(['user', 'items.car', 'shippingAddress', 'billingAddress', 'payments'])->first();
SendOrderConfirmationEmail::dispatch($order);
```

## Monitoring

### Check Queue Status

```bash
php artisan queue:monitor database:default
```

### Failed Jobs

View failed jobs:

```bash
php artisan queue:failed
```

Retry a failed job:

```bash
php artisan queue:retry {id}
```

Retry all failed jobs:

```bash
php artisan queue:retry all
```

Clear failed jobs:

```bash
php artisan queue:flush
```

## Performance Considerations

1. **Queue Workers:** Run multiple queue workers in production (2-4 workers)
2. **Timeout:** Set appropriate timeout for email jobs (default: 60 seconds)
3. **Rate Limiting:** Implement rate limiting for email sending if needed
4. **Async:** All emails are dispatched asynchronously to avoid blocking requests
5. **Failed Jobs:** Monitor and retry failed jobs regularly

## Future Enhancements

-   [ ] Add email templates for abandoned cart recovery
-   [ ] Implement email preferences (allow users to opt-out of certain emails)
-   [ ] Add email analytics (open rates, click rates)
-   [ ] Support multiple languages for email templates
-   [ ] Add SMS notifications as an alternative channel
-   [ ] Implement webhook notifications for third-party integrations
