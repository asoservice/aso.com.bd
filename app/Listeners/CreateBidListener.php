<?php

namespace App\Listeners;

use App\Models\User;
use App\Helpers\Helpers;
use App\Models\SmsTemplate;
use App\Events\CreateBidEvent;
use App\Models\ServiceRequest;
use App\Models\PushNotificationTemplate;
use App\Notifications\CreateBidNotification;

class CreateBidListener
{
    /**
     * Handle the event.
     */
    public function handle(CreateBidEvent $event)
    {
        $serviceRequest = ServiceRequest::findOrFail($event->bid->service_request_id);
        $user = User::findOrFail($serviceRequest->user_id);
        $provider = User::findOrFail($event->bid->provider_id);
        if($user && $provider){
            $message = "You've received a new bid on your service request '{$serviceRequest->title}' from {$provider->name}.";
            $this->sendPushNotification($user->fcm_token, $message, $event);
            // $user->notify(new CreateBidNotification($serviceRequest, $provider));
            $sendTo = ('+'.$user?->code.$user?->phone);
            Helpers::sendSMS($sendTo, $this->getSMSMessage($event));

        }
    }

    public function sendPushNotification($token, $message, $event)
    {
        if ($token) {
            $slug = 'new-bid-notification-consumer';
            $content = PushNotificationTemplate::where('slug', $slug)->first();

            if ($content) {
                $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();
                $data = [
                    '{{service_request_title}}' => $event->bid->serviceRequest->title,
                    '{{provider_name}}' => $event->bid->provider->name,
                ];

                $title = str_replace(array_keys($data), array_values($data), $content->title[$locale]);
                $body = str_replace(array_keys($data), array_values($data), $content->content[$locale]);
            } else {
                $title = "New Service Request Available!";
                $body = $message;
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
                        'service_request_id' => (string) $event->bid->service_request_id,
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
        $slug = 'new-bid-notification-consumer'; 
        $content = SmsTemplate::where('slug', $slug)->first();
        if ($content) {
            $data = [
                '{{service_request_title}}' => $event->bid->serviceRequest->title,
                '{{provider_name}}' => $event->bid->provider->name,
            ];
            
            $message = str_replace(array_keys($data), array_values($data), $content?->content[$locale]);
        } else {
            $message = "You've received a new bid on your service request '{$serviceRequest->title}' from {$provider->name}.";
        }
        return $message;
    }

}

// 83|aKb86QEn2xwZjPQgRB0obmVKFv6ht1ALdYuQpWVT3767f8a2