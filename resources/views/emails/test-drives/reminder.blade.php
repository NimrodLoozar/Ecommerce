<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Drive Reminder</title>
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
            width: 150px;
            height: 110px;
            object-fit: cover;
            border-radius: 8px;
        }
        .car-details {
            flex: 1;
        }
        .car-title {
            font-weight: bold;
            color: #1f2937;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .car-specs {
            font-size: 14px;
            color: #6b7280;
            margin: 5px 0;
        }
        .reminder-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
        .time-display {
            font-size: 28px;
            font-weight: bold;
            color: #92400e;
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
            width: 150px;
        }
        .detail-value {
            color: #1f2937;
            flex: 1;
        }
        .button {
            display: inline-block;
            background: #f59e0b;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .checklist {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .checklist ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .checklist li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
        }
        .checklist li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
            font-size: 18px;
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
        <h1 style="margin: 0;">⏰ Test Drive Reminder</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 18px;">Your test drive is tomorrow!</p>
    </div>

    <div class="content">
        <p>Hi {{ $testDrive->user->name }},</p>
        
        <p>This is a friendly reminder about your upcoming test drive tomorrow.</p>

        <div class="reminder-box">
            <div style="font-size: 16px; color: #92400e; margin-bottom: 5px;">Your appointment is in:</div>
            <div class="time-display">
                {{ $testDrive->preferred_date->format('l, F j') }}<br>
                {{ $testDrive->preferred_time }}
            </div>
            <div style="font-size: 14px; color: #92400e; margin-top: 10px;">
                📅 {{ $testDrive->preferred_date->diffForHumans() }}
            </div>
        </div>

        <div class="car-box">
            @php
                $carImages = $testDrive->car->getFilesystemImages();
                $coverImage = $testDrive->car->getCoverImage($carImages);
            @endphp
            @if($coverImage)
            <img src="{{ asset($coverImage) }}" alt="{{ $testDrive->car->title }}" class="car-image">
            @else
            <div style="width: 150px; height: 110px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                No Image
            </div>
            @endif
            <div class="car-details">
                <div class="car-title">{{ $testDrive->car->title }}</div>
                <div class="car-specs">
                    <strong>{{ $testDrive->car->year }}</strong> • 
                    {{ $testDrive->car->fuel_type }} • 
                    {{ $testDrive->car->transmission }}
                </div>
                <div class="car-specs">
                    {{ number_format($testDrive->car->mileage) }} km • 
                    {{ $testDrive->car->exterior_color }}
                </div>
                <div style="margin-top: 8px; font-weight: bold; color: #f59e0b; font-size: 18px;">
                    €{{ number_format($testDrive->car->price, 2) }}
                </div>
            </div>
        </div>

        <div class="details-box">
            <h3 style="margin-top: 0;">📍 Location & Contact</h3>
            @if($testDrive->car->dealer)
                <div class="detail-row">
                    <div class="detail-label">Dealer:</div>
                    <div class="detail-value">{{ $testDrive->car->dealer->company_name }}</div>
                </div>
                @if($testDrive->car->dealer->phone)
                <div class="detail-row">
                    <div class="detail-label">Phone:</div>
                    <div class="detail-value">{{ $testDrive->car->dealer->phone }}</div>
                </div>
                @endif
            @else
                <div class="detail-row">
                    <div class="detail-label">Location:</div>
                    <div class="detail-value">CarHub Platform Showroom</div>
                </div>
            @endif
            <div class="detail-row">
                <div class="detail-label">Your Phone:</div>
                <div class="detail-value">{{ $testDrive->phone }}</div>
            </div>
        </div>

        <div class="checklist">
            <h3 style="margin-top: 0;">✓ Checklist - Don't Forget:</h3>
            <ul>
                <li>Valid driver's license (required)</li>
                <li>Arrive 10 minutes early</li>
                <li>Comfortable driving shoes</li>
                <li>Any questions you want to ask</li>
                @if($testDrive->car->dealer && $testDrive->car->dealer->phone)
                <li>Call {{ $testDrive->car->dealer->phone }} if running late</li>
                @endif
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('test-drives.index') }}" class="button">View Test Drive Details</a>
        </div>

        <div style="background: #dcfce7; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <strong>💡 Pro Tips:</strong>
            <ul style="margin: 10px 0; padding-left: 20px; font-size: 14px;">
                <li>Test the car on various road conditions if possible</li>
                <li>Check all features: lights, AC, infotainment, etc.</li>
                <li>Ask about service history and warranty</li>
                <li>Don't hesitate to ask questions!</li>
            </ul>
        </div>

        <p>We're looking forward to seeing you tomorrow! If you need to reschedule or have any questions, please contact us immediately.</p>
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
