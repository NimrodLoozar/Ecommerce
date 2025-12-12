<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Purchase</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .content h2 {
            color: #333;
            font-size: 20px;
            margin-top: 0;
        }
        .cart-items {
            margin: 20px 0;
        }
        .cart-item {
            display: flex;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: #f9fafb;
        }
        .cart-item img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        .cart-item-details {
            flex: 1;
        }
        .cart-item-title {
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 5px 0;
        }
        .cart-item-meta {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }
        .cart-item-price {
            font-size: 18px;
            font-weight: 600;
            color: #667eea;
            align-self: center;
        }
        .total-section {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
            text-align: right;
        }
        .total-section p {
            margin: 10px 0;
            font-size: 16px;
        }
        .total-section .total {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .button:hover {
            opacity: 0.9;
        }
        .urgency-note {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .urgency-note p {
            margin: 0;
            color: #92400e;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🛒 Your Cart is Waiting!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Hi {{ $cart->user->name }},</h2>
            <p>We noticed you left some items in your cart. Good news - they're still available!</p>

            <!-- Cart Items -->
            <div class="cart-items">
                @foreach($cart->items as $item)
                    <div class="cart-item">
                        @php
                            $image = $item->car->images->first();
                            $imageUrl = $image 
                                ? asset('storage/' . $image->image_path) 
                                : 'https://via.placeholder.com/80x60?text=' . urlencode($item->car->brand->name);
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $item->car->title }}">
                        <div class="cart-item-details">
                            <p class="cart-item-title">{{ $item->car->title }}</p>
                            <p class="cart-item-meta">
                                {{ $item->car->year }} • {{ $item->car->brand->name }} • {{ $item->car->carModel->name }}
                            </p>
                            <p class="cart-item-meta">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="cart-item-price">
                            €{{ number_format($item->car->price, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="total-section">
                @php
                    $subtotal = $cart->items->sum(function($item) {
                        return $item->car->price * $item->quantity;
                    });
                @endphp
                <p><strong>Subtotal:</strong> €{{ number_format($subtotal, 0, ',', '.') }}</p>
                <p class="total">Total: €{{ number_format($subtotal, 0, ',', '.') }}</p>
            </div>

            <!-- Urgency Note -->
            <div class="urgency-note">
                <p><strong>⏰ Don't miss out!</strong> These cars are in high demand and may not be available for long.</p>
            </div>

            <!-- Call to Action -->
            <div style="text-align: center;">
                <a href="{{ route('cart.index') }}" class="button">
                    Complete Your Purchase
                </a>
            </div>

            <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
                Need help deciding? Our team is here to assist you with any questions.
                <a href="{{ route('contact') }}" style="color: #667eea;">Contact us</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This email was sent because you have items in your shopping cart.</p>
            <p>
                <a href="{{ config('app.url') }}">Visit our website</a> | 
                <a href="mailto:{{ config('mail.from.address') }}">Contact Support</a>
            </p>
            <p style="margin-top: 15px;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
