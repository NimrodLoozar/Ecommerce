<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
        .order-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-details {
            flex: 1;
        }
        .item-name {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .item-specs {
            font-size: 14px;
            color: #6b7280;
        }
        .item-price {
            font-weight: bold;
            color: #4f46e5;
            text-align: right;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }
        .total-row.grand-total {
            border-top: 2px solid #e5e7eb;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }
        .address-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }
        .address-title {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
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
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Thank You for Your Order!</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Order #{{ $order->order_number }}</p>
    </div>

    <div class="content">
        <p>Hi {{ $order->user->name }},</p>
        
        <p>Thank you for your order! We've received your order and will begin processing it shortly.</p>

        <div class="order-details">
            <h2 style="margin-top: 0;">Order Details</h2>
            
            <p>
                <strong>Order Number:</strong> {{ $order->order_number }}<br>
                <strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}<br>
                <strong>Status:</strong> <span class="status-badge">{{ ucfirst($order->status) }}</span>
            </p>

            <h3>Items Ordered:</h3>
            @foreach($order->items as $item)
            <div class="item">
                <div class="item-details">
                    <div class="item-name">{{ $item->car->title }}</div>
                    <div class="item-specs">
                        {{ $item->car->year }} • {{ $item->car->fuel_type }} • {{ $item->car->transmission }}
                    </div>
                    <div class="item-specs">Quantity: {{ $item->quantity }}</div>
                </div>
                <div class="item-price">
                    €{{ number_format($item->price * $item->quantity, 2) }}
                </div>
            </div>
            @endforeach

            <div style="margin-top: 20px;">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>€{{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax (21% VAT):</span>
                    <span>€{{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>€{{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                <div class="total-row grand-total">
                    <span>Total:</span>
                    <span>€{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="address-box">
            <div class="address-title">Shipping Address</div>
            {{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}<br>
            {{ $order->shippingAddress->street }}<br>
            @if($order->shippingAddress->apartment)
            {{ $order->shippingAddress->apartment }}<br>
            @endif
            {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}<br>
            {{ $order->shippingAddress->country->name }}
        </div>

        <div class="address-box">
            <div class="address-title">Payment Method</div>
            {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}<br>
            <strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}
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
