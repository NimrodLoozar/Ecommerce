<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Drive Confirmation</title>
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
            background: #10b981;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .info-box {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
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
        <h1 style="margin: 0;">✓ Test Drive Confirmed</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Your test drive has been scheduled</p>
    </div>

    <div class="content">
        <p>Hi {{ $testDrive->user->name }},</p>
        
        <p>Great news! Your test drive has been confirmed. We're excited to show you this vehicle.</p>

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
                <div style="margin-top: 8px; font-weight: bold; color: #10b981; font-size: 18px;">
                    €{{ number_format($testDrive->car->price, 2) }}
                </div>
            </div>
        </div>

        <div class="info-box">
            <strong>📅 Appointment Details</strong>
        </div>

        <div class="details-box">
            <div class="detail-row">
                <div class="detail-label">Date & Time:</div>
                <div class="detail-value">
                    <strong>{{ $testDrive->preferred_date->format('l, F j, Y') }}</strong><br>
                    {{ $testDrive->preferred_time }}
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Your Name:</div>
                <div class="detail-value">{{ $testDrive->user->name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Phone:</div>
                <div class="detail-value">{{ $testDrive->phone }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status:</div>
                <div class="detail-value">
                    <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-weight: bold; font-size: 14px;">
                        ✓ Confirmed
                    </span>
                </div>
            </div>
            @if($testDrive->notes)
            <div class="detail-row">
                <div class="detail-label">Your Notes:</div>
                <div class="detail-value">{{ $testDrive->notes }}</div>
            </div>
            @endif
        </div>

        <div style="background: #eff6ff; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <strong>📍 Location:</strong><br>
            @if($testDrive->car->dealer)
                {{ $testDrive->car->dealer->company_name }}<br>
                @if($testDrive->car->dealer->phone)
                Phone: {{ $testDrive->car->dealer->phone }}<br>
                @endif
            @else
                CarHub Platform Showroom<br>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ route('test-drives.index') }}" class="button">View My Test Drives</a>
        </div>

        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <strong>⚠️ Important Reminders:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Please bring a valid driver's license</li>
                <li>Arrive 10 minutes early</li>
                <li>We'll send you a reminder 24 hours before your appointment</li>
            </ul>
        </div>

        <p>If you need to reschedule or cancel, please contact us as soon as possible.</p>
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
