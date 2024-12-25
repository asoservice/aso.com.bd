<?php

namespace App\Listeners;

use App\Enums\RoleEnum;
use App\Events\CreateServiceRequestEvent;
use App\Helpers\Helpers;
use App\Models\User;
use App\Notifications\CreateServiceRequestNotification;
use App\Models\PushNotificationTemplate;
use App\Models\SmsTemplate;

class CreateServiceRequestListener
{
    /**
     * Handle the event.
     */
    public function handle(CreateServiceRequestEvent $event)
    {
        $admin = User::role(RoleEnum::ADMIN)->first();
        $providers = User::role(RoleEnum::PROVIDER)->get();
        $tokens = User::whereNotNull('fcm_token')
        ->role(RoleEnum::PROVIDER)
        ->pluck('fcm_token')
        ->all();
        foreach ($tokens as $token) {
            $this->sendPushNotification($token, $event);
        }
        
        if (isset($admin)) {
            $admin->notify(new CreateServiceRequestNotification($event->serviceRequest, RoleEnum::ADMIN));
        }
        
        foreach ($providers as $provider) {
            $sendTo = ('+'.$provider?->code.$provider?->phone);
            Helpers::sendSMS($sendTo, $this->getSMSMessage($event));   
            $provider->notify(new CreateServiceRequestNotification($event->serviceRequest, RoleEnum::PROVIDER));
        }
    }

    public function sendPushNotification($token, $event)
    {
        if ($token) {
            $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();
            $slug = 'new-service-request-provider'; 

            $content = PushNotificationTemplate::where('slug', $slug)->first();
            
            if ($content) {
                $data = [
                    '{{service_request_title}}' => $event?->serviceRequest?->title,
                ];

                $title = $content?->title[$locale];
                $body = str_replace(array_keys($data), array_values($data), $content?->content[$locale]);
            } else {
                $title = "New Service Request Available!";
                $body = "A new service request has been created. Place your bid now.";
            }

            $notification = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => '',
                    ],
                    'data' => [
                        'service_request_id' => (string) $event?->serviceRequest?->id,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'service_request',
                    ],
                ],
            ];
            Helpers::pushNotification($notification);
        }
    }

    public function getSMSMessage($event)
    {
        $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();
        $slug = 'new-service-request-provider'; 
        $content = SmsTemplate::where('slug', $slug)->first();
        
        if ($content) {
            $data = [
                '{{service_request_title}}' => $event?->serviceRequest?->title,
            ];

            $message = str_replace(array_keys($data), array_values($data), $content?->content[$locale]);
        } else {
            $message = "A new service request has been created. Place your bid now.";
        }
        return $message;
    }

}
