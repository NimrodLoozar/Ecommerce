<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Response</title>
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
        .response-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
        }
        .original-inquiry {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 3px solid #9ca3af;
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
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Response to Your Inquiry</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">{{ $inquiry->updated_at->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <div class="content">
        <p>Hi {{ $inquiry->user->name }},</p>
        
        @if($inquiry->car && $inquiry->car->dealer)
        <p>{{ $inquiry->car->dealer->company_name }} has responded to your inquiry:</p>
        @else
        <p>We have responded to your inquiry:</p>
        @endif

        @if($inquiry->car)
        <div class="car-box">
            @php
                $carImages = $inquiry->car->getFilesystemImages();
                $coverImage = $inquiry->car->getCoverImage($carImages);
            @endphp
            @if($coverImage)
            <img src="{{ asset($coverImage) }}" alt="{{ $inquiry->car->title }}" class="car-image">
            @else
            <div style="width: 120px; height: 90px; background: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                No Image
            </div>
            @endif
            <div class="car-details">
                <div class="car-title">{{ $inquiry->car->title }}</div>
                <div class="car-specs">
                    {{ $inquiry->car->year }} • {{ $inquiry->car->fuel_type }} • {{ $inquiry->car->transmission }}
                </div>
                <div style="margin-top: 5px; font-weight: bold; color: #4f46e5;">
                    €{{ number_format($inquiry->car->price, 2) }}
                </div>
            </div>
        </div>
        @endif

        @if($inquiry->response)
        <div class="response-box">
            <h3 style="margin-top: 0; color: #10b981;">Response:</h3>
            <p style="margin-bottom: 0; white-space: pre-line;">{{ $inquiry->response }}</p>
        </div>
        @endif

        <div class="original-inquiry">
            <h4 style="margin-top: 0; color: #6b7280;">Your Original Inquiry:</h4>
            <p style="margin: 5px 0;"><strong>{{ $inquiry->subject }}</strong></p>
            <p style="margin-bottom: 0; white-space: pre-line; color: #6b7280; font-size: 14px;">{{ $inquiry->message }}</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('inquiries.index') }}" class="button">View All Inquiries</a>
        </div>

        <p style="font-size: 14px; color: #6b7280;">
            If you have any additional questions, feel free to submit another inquiry or contact us directly.
        </p>
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
