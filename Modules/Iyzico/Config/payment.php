<?php

return [
    'name' => 'Iyzico',
    'slug' => 'iyzico',
    'title' => 'Iyzico Payment',
    'image' => 'modules/iyzico/images/logo.png',
    'configs' => [
        'iyzico_api_key' => env('IYZICO_API_KEY', 'iyzico_api_key'),
        'iyzico_secret_key' => env('IYZICO_SECRET_KEY', 'iyzico_secret_key'),
        'iyzico_sandbox_mode' => env('IYZICO_SANDBOX_MODE', 'true'),
    ],
    'fields' => [
        'title' => [
            'type' => 'text',
            'label' => 'Label'
        ],
        'iyzico_sandbox_mode' => [
            'type' => 'select',
            'label' => 'Select Mode',
            'options' => [
                '1' => 'Sandbox',
                '0' => 'Live',
            ],
        ],
        'iyzico_secret_key' => [
            'type' => 'password',
            'label' => 'Iyzico Secret Key',
        ],
        'iyzico_api_key' => [
            'type' => 'password',
            'label' => 'Iyzico API Key',
        ]
    ],
];