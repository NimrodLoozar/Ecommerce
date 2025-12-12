# Email System Quick Reference

## Quick Start

### 1. Start the Queue Worker (Development)

```bash
php artisan queue:work
```

### 2. Test Email Locally

Update `.env`:

```env
MAIL_MAILER=log
```

All emails will be logged to `storage/logs/laravel.log`.

### 3. View Logged Emails

```bash
tail -f storage/logs/laravel.log
```

## Common Tasks

### Send Test Order Confirmation

```bash
php artisan tinker
```

```php
use App\Jobs\SendOrderConfirmationEmail;
use App\Models\Order;

$order = Order::with(['user', 'items.car.brand', 'items.car.carModel', 'shippingAddress', 'billingAddress', 'payments'])->first();

if ($order) {
    SendOrderConfirmationEmail::dispatch($order);
    echo "Order confirmation email queued!\n";
} else {
    echo "No orders found. Create an order first.\n";
}
```

### Check Queue Status

```bash
# View all queued jobs
php artisan queue:work --once

# View failed jobs
php artisan queue:failed

# Retry a specific failed job
php artisan queue:retry {id}

# Retry all failed jobs
php artisan queue:retry all

# Clear all failed jobs
php artisan queue:flush
```

### Run Scheduled Commands Manually

```bash
# Send test drive reminders
php artisan test-drives:send-reminders

# Send review requests
php artisan reviews:send-requests
```

### Preview Email in Browser

Add to `routes/web.php`:

```php
Route::get('/preview/order-confirmation', function () {
    $order = \App\Models\Order::with(['user', 'items.car', 'shippingAddress'])->first();
    return new \App\Mail\OrderConfirmationMail($order);
});

Route::get('/preview/order-status-update', function () {
    $order = \App\Models\Order::with(['user', 'items.car'])->first();
    return new \App\Mail\OrderStatusUpdateMail($order, 'pending');
});

Route::get('/preview/test-drive-confirmation', function () {
    $testDrive = \App\Models\TestDrive::with(['user', 'car'])->first();
    return new \App\Mail\TestDriveConfirmationMail($testDrive);
});
```

Visit: `http://localhost:8000/preview/order-confirmation`

## Integration Checklist

### ✅ Already Integrated

-   [x] Order confirmation email (CheckoutController@store)
-   [x] Order status update email (Dealer\OrderController@update)

### 🔲 TODO: Controllers to Update

Add these email dispatches to controllers:

#### InquiryController

```php
use App\Jobs\SendInquiryReceivedEmail;

public function store(Request $request)
{
    // ... validation and creation logic

    SendInquiryReceivedEmail::dispatch($inquiry);

    return redirect()->back()->with('success', 'Inquiry submitted!');
}
```

#### Dealer\InquiryController

```php
use App\Jobs\SendInquiryResponseEmail;

public function update(Request $request, Inquiry $inquiry)
{
    // ... update logic

    if ($inquiry->response) {
        SendInquiryResponseEmail::dispatch($inquiry);
    }

    return redirect()->back()->with('success', 'Response sent!');
}
```

#### TestDriveController

```php
use App\Jobs\SendTestDriveConfirmationEmail;

public function update(Request $request, TestDrive $testDrive)
{
    // ... update logic

    if ($testDrive->status === 'confirmed') {
        SendTestDriveConfirmationEmail::dispatch($testDrive);
    }

    return redirect()->back()->with('success', 'Test drive confirmed!');
}
```

#### TradeInController

```php
use App\Jobs\SendTradeInOfferEmail;

public function update(Request $request, TradeIn $tradeIn)
{
    // ... update logic

    if ($tradeIn->offer_amount) {
        SendTradeInOfferEmail::dispatch($tradeIn);
    }

    return redirect()->back()->with('success', 'Offer sent!');
}
```

#### Admin\DealerController

```php
use App\Jobs\SendDealerApprovalEmail;

public function update(Request $request, DealerProfile $dealer)
{
    $oldStatus = $dealer->status;

    // ... update logic

    if ($oldStatus !== 'approved' && $dealer->status === 'approved') {
        SendDealerApprovalEmail::dispatch($dealer);
    }

    return redirect()->back()->with('success', 'Dealer approved!');
}
```

#### Admin\CommissionController

```php
use App\Jobs\SendCommissionPaymentEmail;

public function markAsPaid(Commission $commission)
{
    $commission->update([
        'status' => 'paid',
        'paid_at' => now(),
    ]);

    SendCommissionPaymentEmail::dispatch($commission);

    return redirect()->back()->with('success', 'Payment processed!');
}
```

## Production Configuration

### 1. Update `.env` for Production

```env
# Use SMTP for real emails
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@carhub.com"
MAIL_FROM_NAME="CarHub Platform"

# Use Redis for better queue performance
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 2. Setup Supervisor (Linux)

Create `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/worker.log
stopwaitsecs=3600
```

Restart Supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### 3. Setup Cron for Scheduler (Linux)

```bash
crontab -e
```

Add:

```
* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1
```

## Troubleshooting

### Emails Not Sending

1. Check queue worker is running: `ps aux | grep queue:work`
2. Check for failed jobs: `php artisan queue:failed`
3. Check logs: `tail -f storage/logs/laravel.log`
4. Verify mail config: `php artisan config:clear`

### Queue Worker Crashes

1. Check worker.log: `tail -f storage/logs/worker.log`
2. Restart supervisor: `sudo supervisorctl restart laravel-worker:*`
3. Check memory limits in `php.ini`

### Emails Going to Spam

1. Configure SPF records for your domain
2. Set up DKIM authentication
3. Use a reputable SMTP provider (Mailgun, SendGrid, SES)
4. Avoid spam trigger words in subject lines

## Email Service Recommendations

### Development

-   **Mailtrap.io** - Free email testing service
-   **Log driver** - Built-in Laravel logging

### Production

-   **Mailgun** - Reliable, good for transactional emails
-   **SendGrid** - Great analytics and templates
-   **Amazon SES** - Cost-effective for high volume
-   **Postmark** - Fast delivery, great for transactional

## Monitoring

### Laravel Horizon (Recommended for Production)

```bash
composer require laravel/horizon
php artisan horizon:install
php artisan migrate
```

Access dashboard: `http://your-site.com/horizon`

### Basic Monitoring

```bash
# Monitor queue in real-time
php artisan queue:monitor database:default --max=100

# Check job statistics
php artisan queue:work --verbose
```
