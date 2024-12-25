<?php

namespace App\Enums;

enum FrontSettingsEnum: string
{
    case GENERAL = 'general';
    case ACTIVATION = 'activation';
    case PROVIDER_COMMISSION = 'provider_commissions';
    case DEFAULT_CREATION_LIMITS = 'default_creation_limits';
    case SUBSCRIPTION_PLAN = 'subscription_plan';
    case AGORA = 'agora';
    case FIREBASE = 'firebase';
    case SERVICE_REQUEST = 'service_request';
}
