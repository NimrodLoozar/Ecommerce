<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dealer Account Approved</title>
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
            padding: 40px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .success-box {
            background: #dcfce7;
            border: 2px solid #10b981;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .success-icon {
            font-size: 60px;
            margin-bottom: 10px;
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
        .feature-list {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .feature-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .feature-list li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
            border-bottom: 1px solid #e5e7eb;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
            font-size: 20px;
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
        <h1 style="margin: 0; font-size: 32px;">🎉 Congratulations!</h1>
        <p style="margin: 15px 0 0 0; opacity: 0.9; font-size: 18px;">Your dealer account has been approved</p>
    </div>

    <div class="content">
        <p>Hi {{ $dealerProfile->user->name }},</p>
        
        <p>We're excited to inform you that your dealer application has been approved! Welcome to the CarHub Platform dealer network.</p>

        <div class="success-box">
            <div class="success-icon">✓</div>
            <h2 style="margin: 10px 0; color: #065f46;">Account Approved!</h2>
            <p style="margin: 5px 0; color: #059669;">You can now start listing your vehicles</p>
        </div>

        <div class="details-box">
            <h3 style="margin-top: 0;">Your Dealer Account Details</h3>
            <div class="detail-row">
                <div class="detail-label">Company Name:</div>
                <div class="detail-value"><strong>{{ $dealerProfile->company_name }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Subscription Plan:</div>
                <div class="detail-value">{{ ucfirst($dealerProfile->subscription_plan) }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Commission Rate:</div>
                <div class="detail-value">{{ $dealerProfile->commission_rate }}%</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Approval Date:</div>
                <div class="detail-value">{{ $dealerProfile->approved_at->format('F j, Y') }}</div>
            </div>
        </div>

        <div class="feature-list">
            <h3 style="margin-top: 0;">What You Can Do Now:</h3>
            <ul>
                <li>Add unlimited vehicle listings</li>
                <li>Manage your inventory dashboard</li>
                <li>Track orders and sales</li>
                <li>Respond to customer inquiries</li>
                <li>Schedule test drives</li>
                <li>View analytics and reports</li>
                <li>Manage commission payments</li>
                <li>Update your dealer profile</li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('dealer.dashboard') }}" class="button">Go to Your Dashboard</a>
        </div>

        <div style="background: #eff6ff; padding: 15px; border-radius: 6px; border-left: 4px solid #3b82f6; margin: 20px 0;">
            <strong>📚 Getting Started:</strong>
            <ol style="margin: 10px 0; padding-left: 20px;">
                <li>Complete your dealer profile with logo and documents</li>
                <li>Add your first vehicle listing</li>
                <li>Set up your bank account for commission payments</li>
                <li>Explore the dealer dashboard features</li>
            </ol>
        </div>

        <div style="background: #fef3c7; padding: 15px; border-radius: 6px; border-left: 4px solid #f59e0b; margin: 20px 0;">
            <strong>💡 Support:</strong><br>
            If you have any questions or need assistance, our support team is here to help. Visit the Help Center or contact us directly.
        </div>

        <p>We're thrilled to have you as part of our dealer network. Let's work together to provide the best car buying experience for our customers!</p>
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
