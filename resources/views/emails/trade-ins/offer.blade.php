<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade-In Offer</title>
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
        .offer-box {
            background: #dcfce7;
            border: 2px solid #10b981;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .offer-amount {
            font-size: 36px;
            font-weight: bold;
            color: #065f46;
            margin: 15px 0;
        }
        .vehicle-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
            width: 140px;
        }
        .detail-value {
            color: #1f2937;
            flex: 1;
        }
        .button {
            display: inline-block;
            background: #10b981;
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
        <h1 style="margin: 0;">💰 Trade-In Offer Received!</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">We've evaluated your vehicle</p>
    </div>

    <div class="content">
        <p>Hi {{ $tradeIn->user->name }},</p>
        
        <p>Great news! We've completed the evaluation of your trade-in vehicle and are pleased to present you with an offer.</p>

        <div class="offer-box">
            <div style="font-size: 18px; color: #065f46; margin-bottom: 5px;">Our Offer for Your Vehicle</div>
            <div class="offer-amount">€{{ number_format($tradeIn->offer_amount, 2) }}</div>
            <div style="font-size: 14px; color: #059669; margin-top: 10px;">
                Valid for 7 days from today
            </div>
        </div>

        <div class="vehicle-box">
            <h3 style="margin-top: 0;">Your Vehicle Details</h3>
            <div class="detail-row">
                <div class="detail-label">Vehicle:</div>
                <div class="detail-value"><strong>{{ $tradeIn->year }} {{ $tradeIn->make }} {{ $tradeIn->model }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Mileage:</div>
                <div class="detail-value">{{ number_format($tradeIn->mileage) }} km</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Condition:</div>
                <div class="detail-value">{{ ucfirst($tradeIn->condition) }}</div>
            </div>
            @if($tradeIn->vin)
            <div class="detail-row">
                <div class="detail-label">VIN:</div>
                <div class="detail-value">{{ $tradeIn->vin }}</div>
            </div>
            @endif
            @if($tradeIn->dealer_notes)
            <div class="detail-row">
                <div class="detail-label">Notes:</div>
                <div class="detail-value">{{ $tradeIn->dealer_notes }}</div>
            </div>
            @endif
        </div>

        <div style="background: #eff6ff; padding: 15px; border-radius: 6px; border-left: 4px solid #3b82f6; margin: 20px 0;">
            <strong>Next Steps:</strong>
            <ol style="margin: 10px 0; padding-left: 20px;">
                <li>Review the offer details</li>
                <li>Contact us if you have any questions</li>
                <li>Accept the offer within 7 days to proceed</li>
                <li>We'll arrange vehicle inspection and paperwork</li>
            </ol>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('trade-ins.show', $tradeIn) }}" class="button">View Trade-In Details</a>
        </div>

        <div style="background: #fef3c7; padding: 15px; border-radius: 6px; border-left: 4px solid #f59e0b; margin: 20px 0;">
            <strong>⚠️ Important:</strong><br>
            This offer is valid for 7 days and is subject to final vehicle inspection. The final offer may be adjusted based on the actual condition of the vehicle.
        </div>

        <p>If you have any questions or would like to accept this offer, please contact us. We're here to help!</p>
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
