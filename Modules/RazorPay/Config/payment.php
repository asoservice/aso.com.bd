<?php

return [
    'name' => 'RazorPay',
    'slug' => 'razorpay',
    'title' => 'RazorPay Payment', 
    'image' => 'modules/razorpay/images/logo.png',
    'configs' => [
        'razorpay_key' => env('RAZORPAY_KEY', 'razorpay_key'),
        'razorpay_secret' => env('RAZORPAY_SECRET', 'razorpay_secret'),
        'razorpay_webhook_secret_key' => env('RAZORPAY_WEBHOOK_SECRET_KEY', 'razorpay_webhook_secret_key'),
        'razorpay_mode' => env('RAZORPAY_MODE', 'sandbox'),
    ],
    'fields' => [
        'title' => [
            'type' => 'text',
            'label' => 'Label'
        ],
        'razorpay_key' => [
            'type' => 'password',
            'label' => 'RazorPay Key',
        ],
        'razorpay_secret' => [
            'type' => 'password',
            'label' => 'RazorPay Secret',
        ],
        'razorpay_webhook_secret_key' => [
            'type' => 'password',
            'label' => 'Webhook Secret Key',
        ],
    ],

];
