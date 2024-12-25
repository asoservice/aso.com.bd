<?php

return [
    'name' => 'Instamojo',
    'slug' => 'instamojo',
    'title' => 'Instamojo Payment',

    'image' => 'modules/instamojo/images/logo.png',
    'configs' => [
        'instamojo_client_id' => env('INSTAMOJO_CLIENT_ID', 'instamojo_client_id'),
        'instamojo_client_secret' => env('INSTAMOJO_CLIENT_SECRET', 'instamojo_client_secret'),
        'instamojo_salt_key' => env('INSTAMOJO_SALT_KEY', 'instamojo_salt_key'),
        'instamojo_sandbox_mode' => env('INSTAMOJO_SANDBOX_MODE', 'true'),
    ],
    'fields' => [
        'title' => [
            'type' => 'text',
            'label' => 'Label'
        ],
        'instamojo_sandbox_mode' => [
            'type' => 'select',
            'label' => 'Select Mode',
            'options' => [
                '1' => 'Sandbox',
                '0' => 'Live',
            ],
        ],
        'instamojo_client_id' => [
            'type' => 'password',
            'label' => 'Client ID',
        ],
        'instamojo_client_secret' => [
            'type' => 'password',
            'label' => 'Client Secret',
        ],
        'instamojo_salt_key' => [
            'type' => 'password',
            'label' => 'Salt Key',
        ],
    ],

];
