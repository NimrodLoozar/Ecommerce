<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
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
            background: linear-gradient(to right, #4f46e5, #7c3aed);
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
        .status-update {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            margin: 10px;
        }
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.confirmed { background: #dbeafe; color: #1e40af; }
        .status-badge.processing { background: #e0e7ff; color: #4338ca; }
        .status-badge.shipped { background: #ddd6fe; color: #5b21b6; }
        .status-badge.delivered { background: #dcfce7; color: #166534; }
        .status-badge.completed { background: #d1fae5; color: #065f46; }
        .status-badge.cancelled { background: #fee2e2; color: #991b1b; }
        .arrow {
            font-size: 24px;
            color: #9ca3af;
            margin: 0 10px;
        }
        .order-summary {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #4f46e5;
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
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Order Status Updated</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Order #{{ $order->order_number }}</p>
    </div>

    <div class="content">
        <p>Hi {{ $order->user->name }},</p>
        
        <p>Your order status has been updated:</p>

        <div class="status-update">
            <span class="status-badge {{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span>
            <span class="arrow">→</span>
            <span class="status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </div>

        @if($order->status === 'confirmed')
        <div class="info-box">
            <strong>Great news!</strong> Your order has been confirmed and is being prepared for processing.
        </div>
        @elseif($order->status === 'processing')
        <div class="info-box">
            <strong>Your order is being processed.</strong> We're preparing your vehicle(s) for shipment.
        </div>
        @elseif($order->status === 'shipped')
        <div class="info-box">
            <strong>Your order has been shipped!</strong> Your vehicle(s) are on their way to you. You should receive them within 3-5 business days.
        </div>
        @elseif($order->status === 'delivered')
        <div class="info-box">
            <strong>Your order has been delivered!</strong> We hope you enjoy your new vehicle. Please let us know if everything is to your satisfaction.
        </div>
        @elseif($order->status === 'completed')
        <div class="info-box">
            <strong>Order completed!</strong> Thank you for your business. We'd love to hear about your experience.
        </div>
        @elseif($order->status === 'cancelled')
        <div class="info-box" style="background: #fef2f2; border-left-color: #ef4444;">
            <strong>Order cancelled.</strong> Your order has been cancelled. If you have any questions, please contact us.
        </div>
        @endif

        <div class="order-summary">
            <h3 style="margin-top: 0;">Order Summary</h3>
            <p>
                <strong>Order Number:</strong> {{ $order->order_number }}<br>
                <strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}<br>
                <strong>Items:</strong> {{ $order->items->count() }} item(s)<br>
                <strong>Total:</strong> €{{ number_format($order->total, 2) }}
            </p>

            <h4>Items:</h4>
            @foreach($order->items as $item)
            <div style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                <strong>{{ $item->car->title }}</strong><br>
                <span style="color: #6b7280; font-size: 14px;">
                    {{ $item->car->year }} • {{ $item->car->fuel_type }} • Qty: {{ $item->quantity }}
                </span>
            </div>
            @endforeach
        </div>

        <div style="text-align: center;">
            <a href="{{ route('orders.show', $order) }}" class="button">View Order Details</a>
        </div>

        <p>If you have any questions about your order, please don't hesitate to contact us.</p>
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
