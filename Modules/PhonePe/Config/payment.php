<?php

return [
    'name' => 'PhonePe',
    'slug' => 'phonepe',
    'title' => 'PhonePe Payment', 

    'image' => 'modules/phonepe/images/logo.png',
    'configs' => [
        'phonepe_merchant_id' => env('PHONEPE_MERCHANT_ID', 'phonepe_merchant_id'),
        'phonepe_salt_key' => env('PHONEPE_SALT_KEY', 'phonepe_salt_key'),
        'phonepe_salt_index' => env('PHONEPE_SALT_INDEX', '1'),
        'phonepe_sandbox_mode' => env('PHONEPE_SANDBOX_MODE', 'true'),
    ],
    'fields' => [
        'title' => [
            'type' => 'text',
            'label' => 'Label'
        ],
        'phonepe_sandbox_mode' => [
            'type' => 'select',
            'label' => 'Select Mode',
            'options' => [
                '1' => 'Sandbox',
                '0' => 'Live',
            ],
        ],
        'phonepe_merchant_id' => [
            'type' => 'password',
            'label' => 'Merchant ID',
        ],
        'phonepe_salt_key' => [
            'type' => 'password',
            'label' => 'Salt Key',
        ],
        'phonepe_salt_index' => [
            'type' => 'password',
            'label' => 'Salt Index',
        ],
    ],
];
