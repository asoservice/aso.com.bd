<?php

return [
    'name' => 'BKash',
    'slug' => 'bkash',
    'title' => 'Bkash Payment',
    'image' => 'modules/bkash/images/logo.png',
    'configs' => [
        'bkash_app_key' => env('BKASH_APP_KEY'),
        'bkash_app_secret' => env('BKASH_APP_SECRET'),
        'bkash_username' => env('BKASH_USERNAME'),
        'bkash_password' => env('BKASH_PASSWORD'),
        'bkash_sandbox_mode' => env('BKASH_SANDBOX_MODE'),
    ],
    'fields' => [
        'title' => [
            'type' => 'text',
            'label' => 'Label',  
        ],
        'bkash_sandb    ox_mode' => [
            'type' => 'select',
            'label' => 'Select Mode',
            'options' => [
                '1' => 'Sandbox',
                '0' => 'Live',
            ],
        ],
        'bkash_app_key' => [
            'type' => 'password',
            'label' => 'BKash App Key',
        ],
        'bkash_app_secret' => [
            'type' => 'password',
            'label' => 'BKash App Secret',
        ],
        'bkash_username' => [
            'type' => 'password',
            'label' => 'BKash Username',
        ],
        'bkash_password' => [
            'type' => 'password',
            'label' => 'BKash Password',
        ],
    ],
];
