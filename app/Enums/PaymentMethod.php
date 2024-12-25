<?php

namespace App\Enums;

enum PaymentMethod
{
    const COD = 'cash';

    const PAYPAL = 'paypal';

    const STRIPE = 'stripe';

    const MOLLIE = 'mollie';

    const RAZORPAY = 'razorpay';

    const WALLET = 'wallet';

    const ALL_PAYMENT_METHODS = [
        'cash', 'paypal', 'stripe', 'mollie', 'razorpay',
    ];
}
