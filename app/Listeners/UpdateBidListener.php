<?php

namespace App\Listeners;

use Exception;
use App\Enums\BidStatusEnum;
use App\Enums\RoleEnum;
use App\Events\UpdateBidEvent;
use App\Helpers\Helpers;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\UpdateBidNotification;
use App\Models\PushNotificationTemplate;
use App\Models\SmsTemplate;

class UpdateBidListener
{
 
    public function handle(UpdateBidEvent $event)
    {
        $serviceRequest = ServiceRequest::findOrFail($event->bid->service_request_id);
        $provider = User::findOrFail($event->bid->provider_id);
        $admin = User::role(RoleEnum::ADMIN)->first();
        $user = User::findOrFail($serviceRequest->user_id);

       
        if ($event->bid->status == BidStatusEnum::ACCEPTED) {
            $this->sendNotifications($provider, $serviceRequest, BidStatusEnum::ACCEPTED, $user, $admin);
        } elseif ($event->bid->status == BidStatusEnum::REJECTED) {
            $this->sendNotifications($provider, $serviceRequest, BidStatusEnum::REJECTED, $user, $admin);
        }
        $this->sendNotifications($admin, $serviceRequest, $event->bid->status, $user, $admin);
    }

    protected function sendNotifications(User $recipient, ServiceRequest $serviceRequest, string $status, User $user, User $admin)
    {
        $this->sendPushNotification($recipient->fcm_token, $status, $serviceRequest, $user);
        $recipient->notify(new UpdateBidNotification($serviceRequest, $user, $status));
        $sendTo = ('+'.$recipient?->code.$recipient?->phone);
        Helpers::sendSMS($sendTo, $this->getSMSMessage($status, $serviceRequest, $user));
        
    }
    
    protected function sendPushNotification($fcmToken, string $status, ServiceRequest $serviceRequest, User $user)
    {
        if ($fcmToken) {
            $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();
            $slug = 'update-bid-provider';
            
            $content = PushNotificationTemplate::where('slug', $slug)->first();
    
            if ($content) {
                $data = [
                    '{{service_request_title}}' => $serviceRequest->title,
                    '{{user_name}}' => $user->name,
                    '{{status}}' => $status
                ];
                
                $title = $content->title[$locale];
                $body = str_replace(array_keys($data), array_values($data), $content->content[$locale]);
            } else {
               
                $title = 'Bid Status Updated';
                $body = "The bid for service request \"{$serviceRequest->title}\" has been {$status} by {$user->name}.";
            }
            
            
            $notification = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => [
                        'service_request_id' => (string) $serviceRequest->id,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'bid_status',
                    ],
                ],
            ];

            Helpers::pushNotification($notification);
        }
    }
    public function getSMSMessage(string $status, ServiceRequest $serviceRequest, User $user)
    {
        $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();
        $slug = 'update-bid-provider';

        $content = PushNotificationTemplate::where('slug', $slug)->first();

      
        if ($content) {
            $data = [
                '{{service_request_title}}' => $serviceRequest->title,
                '{{user_name}}' => $user->name,
                '{{status}}' => $status
            ];

            $message = str_replace(array_keys($data), array_values($data), $content->content[$locale]);
        } else {
            $message = "The bid for service request \"{$serviceRequest->title}\" has been {$status} by {$user->name}.";
        }
        return $message;
    }
}

// 85|4y0XYA4S7Bil32LTANW2L4y3RZzJCJRyCbJulWgM76d8796d
// 86|tTTigu10STDkPwjLJtRhXBEFC0aJfso6Q6WyqQnl9fe86df0