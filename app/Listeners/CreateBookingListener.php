<?php

namespace App\Listeners;

use Exception;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Helpers\Helpers;
use App\Models\SmsTemplate;
use App\Events\CreateBookingEvent;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\PushNotificationTemplate;
use App\Notifications\CreateBookingNotification;

class CreateBookingListener
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(CreateBookingEvent $event)
    {
        try {
            $consumer = $event->booking->consumer;
            if (isset($consumer) && is_null($event->booking->parent_id)) {
                $consumer->notify(new CreateBookingNotification($event->booking, RoleEnum::CONSUMER));
            }

            if ($event->booking->provider_id) {
                $provider = Helpers::getProviderById($event->booking->provider_id);
                
                $this->sendPushNotification($provider?->fcm_token, $event,$event, RoleEnum::PROVIDER);
                $provider->notify(new CreateBookingNotification($event->booking, RoleEnum::PROVIDER));
                $sendTo = ('+'.$provider?->code.$provider?->phone);
                Helpers::sendSMS($sendTo, $this->getSMSMessage($event, RoleEnum::PROVIDER));
            }

            $admin = User::role(RoleEnum::ADMIN)->first();
            if (isset($admin)) {
                $this->sendPushNotification($admin?->fcm_token, $event, RoleEnum::ADMIN);
                $admin->notify(new CreateBookingNotification($event->booking, RoleEnum::ADMIN));
                $sendTo = ('+'.$admin?->code.$admin?->phone);
                Helpers::sendSMS($sendTo, $this->getSMSMessage($event, RoleEnum::ADMIN));
            }

        } catch (Exception $e) {

        }
    }

    public function sendPushNotification($token, $event, $role)
    {
        $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();
        $slug = '';
    
        switch ($role) {
            case 'admin':
                $slug = 'booking-created-admin';
                break;
            case 'provider':
                $slug = 'booking-created-provider';
                break;
        }
        
        $content = PushNotificationTemplate::where('slug', $slug)->first();
    
        if ($content) {
            $data = [
                '{{booking_number}}' => $event->booking?->booking_number,
            ];
    
            $title = $content->title[$locale];
            $body = str_replace(array_keys($data), array_values($data), $content->content[$locale]);
        } else {
           
            $title = "A booking #{$event->booking?->booking_number} has been placed";
            $body = 'Congratulations! A new booking has been received.';
        }
    
        if ($token) {
            $notification = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => "",
                    ],
                    'data' => [
                        'click_action' => "FLUTTER_NOTIFICATION_CLICK",
                        'type' => 'booking',
                        'booking_id' => (string) $event?->booking?->id,
                    ],
                ],
            ];
    
            Helpers::pushNotification($notification);
        }
    }

    public function getSMSMessage($event, $role)
    {
        $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();
        $slug = ''; 
        switch ($role) {
            case 'admin':
                $slug = 'booking-created-admin';
                break;
            case 'provider':
                $slug = 'booking-created-provider';
                break;
        }
    
        $content = SmsTemplate::where('slug', $slug)->first();
        if ($content) {
            $data = [
                '{{booking_number}}' => $event->booking?->booking_number,
            ];
            $message = str_replace(array_keys($data), array_values($data), $content->content[$locale]);
        }  else {
            $message = 'Congratulations! A new booking has been received.';
        }
        return $message;
    }
}
