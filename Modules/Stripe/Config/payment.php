<?php

return [
    'name' => 'Stripe',
    'slug' => 'stripe',
    'title' => 'Stripe Payment',
    'image' => 'modules/stripe/images/logo.png',
    'configs' => [
        'stripe_api_key' => env('STRIPE_API_KEY'),
        'stripe_secret_key' => env('STRIPE_SECRET_KEY'),
        'stripe_webhook_secret_key' => env('STRIPE_WEBHOOK_SECRET_KEY'),
        'stripe_mode' => env('STRIPE_SANDBOX_MOD'),
    ],
    'fields' => [
        'title' => [
            'type' => 'text',
            'label' => 'Label'
        ],
        'stripe_api_key' => [
            'type' => 'password',
            'label' => 'API Key',
        ],
        'stripe_secret_key' => [
            'type' => 'password',
            'label' => 'Secret Key',
        ],
        'stripe_webhook_secret_key' => [
            'type' => 'password',
            'label' => 'Webhook Secret Key',
        ],
    ],
];
