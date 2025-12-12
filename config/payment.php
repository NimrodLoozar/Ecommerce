<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment gateway that will be used
    | for processing payments. Supported: "stripe", "mollie", "paypal"
    |
    */

    'default' => env('PAYMENT_GATEWAY', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Stripe API keys and webhook secret.
    | You can obtain these from your Stripe dashboard.
    |
    */

    'stripe' => [
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'currency' => env('PAYMENT_CURRENCY', 'eur'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mollie Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Mollie API key for EU-focused payments.
    | You can obtain this from your Mollie dashboard.
    |
    */

    'mollie' => [
        'api_key' => env('MOLLIE_API_KEY'),
        'currency' => env('PAYMENT_CURRENCY', 'eur'),
    ],

    /*
    |--------------------------------------------------------------------------
    | PayPal Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your PayPal API credentials for payment processing.
    |
    */

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
        'currency' => env('PAYMENT_CURRENCY', 'eur'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    |
    | The default currency for all payment transactions.
    |
    */

    'currency' => env('PAYMENT_CURRENCY', 'eur'),

    /*
    |--------------------------------------------------------------------------
    | Tax Rate
    |--------------------------------------------------------------------------
    |
    | The default tax rate (VAT) applied to orders.
    |
    */

    'tax_rate' => env('TAX_RATE', 0.21), // 21% VAT

];
