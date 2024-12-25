<?php

return [
    'name' => 'Flutterwave',
    'slug' => 'flutterwave',
    'title' => 'Flutterwave Payment',
    'image' => 'modules/flutterwave/images/logo.png',
    'configs' => [
        'flw_public_key' => env('FLW_PUBLIC_KEY'),
        'flw_secret_key' => env('FLW_SECRET_KEY'),
        'flw_secret_hash' => env('FLW_SECRET_HASH'),
        'flw_sandbox_mode' => env('FLW_SANDBOX_MOD'),
    ],
    'fields' => [
        'title' => [
            'type' => 'text',
            'label' => 'Label'
        ],
        'flw_public_key' => [
            'type' => 'password',
            'label' => 'Public Key',
        ],
        'flw_secret_key' => [
            'type' => 'password',
            'label' => 'Secret Key',
        ],
        'flw_secret_hash' => [
            'type' => 'password',
            'label' => 'Secret Hash',
        ],
    ],

];
