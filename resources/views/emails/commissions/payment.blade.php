<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(to right, #10b981, #14b8a6);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .payment-box {
            background: #dcfce7;
            border: 2px solid #10b981;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .payment-amount {
            font-size: 42px;
            font-weight: bold;
            color: #065f46;
            margin: 15px 0;
        }
        .details-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
            width: 160px;
        }
        .detail-value {
            color: #1f2937;
            flex: 1;
        }
        .button {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">💰 Commission Payment Processed</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Your commission has been paid</p>
    </div>

    <div class="content">
        <p>Hi {{ $commission->dealerProfile->user->name }},</p>
        
        <p>Great news! Your commission payment has been processed and should appear in your account within 2-3 business days.</p>

        <div class="payment-box">
            <div style="font-size: 18px; color: #065f46; margin-bottom: 5px;">Commission Payment</div>
            <div class="payment-amount">€{{ number_format($commission->amount, 2) }}</div>
            <div style="font-size: 14px; color: #059669; margin-top: 10px;">
                Processed on {{ now()->format('F j, Y') }}
            </div>
        </div>

        <div class="details-box">
            <h3 style="margin-top: 0;">Payment Details</h3>
            <div class="detail-row">
                <div class="detail-label">Order Number:</div>
                <div class="detail-value"><strong>{{ $commission->order->order_number }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Order Date:</div>
                <div class="detail-value">{{ $commission->order->created_at->format('F j, Y') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Order Total:</div>
                <div class="detail-value">€{{ number_format($commission->order->total, 2) }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Commission Rate:</div>
                <div class="detail-value">{{ $commission->rate }}%</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Commission Amount:</div>
                <div class="detail-value"><strong>€{{ number_format($commission->amount, 2) }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Payment Status:</div>
                <div class="detail-value">
                    <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-weight: bold; font-size: 14px;">
                        ✓ Paid
                    </span>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Payment Date:</div>
                <div class="detail-value">{{ $commission->paid_at ? $commission->paid_at->format('F j, Y \a\t g:i A') : now()->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>

        <div style="background: #eff6ff; padding: 15px; border-radius: 6px; border-left: 4px solid #3b82f6; margin: 20px 0;">
            <strong>📋 Order Summary:</strong><br>
            Customer: {{ $commission->order->user->name }}<br>
            Items: {{ $commission->order->items->count() }} vehicle(s)<br>
            Status: {{ ucfirst($commission->order->status) }}
        </div>

        <div style="text-align: center;">
            <a href="{{ route('dealer.commissions.index') }}" class="button">View All Commissions</a>
        </div>

        <div style="background: #d1fae5; padding: 15px; border-radius: 6px; border-left: 4px solid #10b981; margin: 20px 0;">
            <strong>💡 Note:</strong> Bank transfers typically take 2-3 business days to appear in your account. You can view all your commission history in your dealer dashboard.
        </div>

        <p>Thank you for being a valued member of the CarHub Platform dealer network!</p>
    </div>

    <div class="footer">
        <p>
            <strong>CarHub Platform</strong><br>
            Car Sales & Leasing<br>
            This is an automated message, please do not reply to this email.
        </p>
    </div>
</body>
</html>
