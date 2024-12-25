<?php

return [
    'name' => 'Midtrans',
    'slug' => 'Midtrans',
    'title' => 'Midtrans Payment',
    'image' => 'modules/midtrans/images/logo.png',
    'configs' => [
        'merchant_id' => env('MERCHANT_ID'),
        'client_key' => env('CLIENT_KEY'),
        'server_key' => env('SERVER_KEY'),
        'midtrans_sandbox_mode' => env('MIDTRANS_SANDBOX_MODE'),
    ],
    'fields' => [
        'title' => [
            'type' => 'text',
            'label' => 'Label'
        ],
        'midtrans_sandbox_mode' => [
            'type' => 'select',
            'label' => 'Select Mode',
            'options' => [
                '1' => 'Sandbox',
                '0' => 'Live',
            ],
        ],
        'merchant_id' => [
            'type' => 'password',
            'label' => 'Merchant ID',
        ],
        'client_key' => [
            'type' => 'password',
            'label' => 'Client Key',
        ],
        'server_key' => [
            'type' => 'password',
            'label' => 'Server Key',
        ]
    ],
];
