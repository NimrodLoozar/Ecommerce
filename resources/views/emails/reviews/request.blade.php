<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Request</title>
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
            background: linear-gradient(to right, #f59e0b, #f97316);
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
        .stars {
            font-size: 40px;
            text-align: center;
            margin: 20px 0;
        }
        .car-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .car-image {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
        }
        .car-details {
            flex: 1;
        }
        .car-title {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .car-specs {
            font-size: 14px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            background: #f59e0b;
            color: white;
            padding: 14px 35px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
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
        <h1 style="margin: 0;">⭐ How's Your New Vehicle?</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">We'd love to hear from you!</p>
    </div>

    <div class="content">
        <p>Hi {{ $order->user->name }},</p>
        
        <p>We hope you're enjoying your new vehicle! It's been a week since your purchase, and we'd love to hear about your experience.</p>

        <div class="stars">⭐⭐⭐⭐⭐</div>

        @foreach($order->items as $item)
        <div class="car-box">
            @php
                $carImages = $item->car->getFilesystemImages();
                $coverImage = $item->car->getCoverImage($carImages);
            @endphp
            @if($coverImage)
            <img src="{{ asset($coverImage) }}" alt="{{ $item->car->title }}" class="car-image">
            @else
            <div style="width: 120px; height: 90px; background: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                No Image
            </div>
            @endif
            <div class="car-details">
                <div class="car-title">{{ $item->car->title }}</div>
                <div class="car-specs">
                    {{ $item->car->year }} • {{ $item->car->fuel_type }} • {{ $item->car->transmission }}
                </div>
                <div style="margin-top: 5px; color: #6b7280; font-size: 14px;">
                    Delivered {{ $order->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach

        <div style="background: #fef3c7; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
            <h3 style="margin-top: 0; color: #92400e;">Your Opinion Matters!</h3>
            <p style="margin: 10px 0;">Help other buyers make informed decisions by sharing your experience.</p>
            <p style="font-size: 14px; color: #92400e; margin-bottom: 0;">
                ✓ Rate your vehicle<br>
                ✓ Share your thoughts<br>
                ✓ Help the community
            </p>
        </div>

        <div style="text-align: center;">
            @foreach($order->items as $item)
            <a href="{{ route('cars.show', $item->car) }}#write-review" class="button">Write a Review for {{ $item->car->brand->name ?? 'this' }} {{ $item->car->carModel->name ?? 'vehicle' }}</a>
            <br>
            @endforeach
        </div>

        <div style="background: #d1fae5; padding: 15px; border-radius: 6px; border-left: 4px solid #10b981; margin: 20px 0;">
            <strong>💡 Tip:</strong> Reviews help us improve our service and assist other customers in making the right choice. Your honest feedback is invaluable!
        </div>

        <p>Thank you for choosing CarHub Platform. We appreciate your business!</p>
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
