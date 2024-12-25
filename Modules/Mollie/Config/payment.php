<?php

return array (
  'name' => 'Mollie',
  'slug' => 'mollie',
  'title' => 'Mollie Payment',
  'image' => 'modules/mollie/images/logo.png',
  'configs' => 
  array (
    'mollie_key' => 'mollie_key',
    'mollie_webhook_url' => 'mollie_webhook_url',
    'mollie_mode' => 'sandbox',
  ),
  'fields' => 
  array (
    'title' => 
    array (
      'type' => 'text',
      'label' => 'Label',
    ),
    'mollie_key' => 
    array (
      'type' => 'password',
      'label' => 'Mollie Key',
    ),
    'mollie_webhook_url' => 
    array (
      'type' => 'password',
      'label' => 'Webhook URL',
    ),
  ),
);
