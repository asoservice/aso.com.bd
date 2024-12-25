<?php

namespace App\Listeners;

use App\Events\UpdateBookingStatusEvent;
use App\Helpers\Helpers;
use App\Notifications\UpdateBookingStatusNotification;
use Exception;
use App\Models\PushNotificationTemplate;
use App\Models\SmsTemplate;

class UpdateBookingStatusListener
{
    /**
     * Handle the event.
     */
    public function handle(UpdateBookingStatusEvent $event)
    {
        try {

            if ($event->booking->consumer_id) {
                $consumer = Helpers::getConsumerById($event->booking->consumer_id);
                if ($consumer) {
                    $this->sendPushNotification($consumer->fcm_token, $event);
                    $consumer->notify(new UpdateBookingStatusNotification($event->booking, $consumer));
                    $sendTo = ('+'.$consumer?->code.$consumer?->phone);
                    Helpers::sendSMS($sendTo, $this->getSMSMessage($event));
                }
            }

        } catch (Exception $e) {

            //
        }
    }

    public function sendPushNotification($token, $event)
    {
        if ($token) {
            $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();

            $slug = 'update-booking-status-consumer'; 

            $content = PushNotificationTemplate::where('slug', $slug)->first();
            $title = '';
            $body = '';

            if ($content) {
                $data = [
                    '{{booking_number}}' => $event->booking?->booking_number,
                    '{{status}}' => $event->booking?->booking_status?->name,
                ];

                $title = $content->title[$locale];
                $body = str_replace(array_keys($data), array_values($data), $content->content[$locale]);
            } else {
                $title = "Booking status is {$event->booking?->booking_status?->name}";
                $body = "Booking Number: #{$event->booking?->booking_number} has been {$event->booking?->booking_status?->name}";
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
                        'booking_id' => (string) $event?->booking?->id,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'booking',
                    ],
                ],
            ];

            Helpers::pushNotification($notification);
        }
    }

    public function getSMSMessage($event)
    {
        $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();
        $slug = 'update-booking-status-consumer'; 
        
        $content = SmsTemplate::where('slug', $slug)->first();
        if ($content) {
            $data = [
                '{{booking_number}}' => $event->booking?->booking_number,
                '{{status}}' => $event->booking?->booking_status?->name,
            ];
            $message = str_replace(array_keys($data), array_values($data), $content->content[$locale]);
        }  else {
            $message = "Booking Number: #{$event->booking?->booking_number} has been {$event->booking?->booking_status?->name}";
        }
    
        return $message;
    }
}
