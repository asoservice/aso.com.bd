<?php

namespace App\Listeners;

use App\Enums\RoleEnum;
use App\Events\VerifyProofEvent;
use App\Helpers\Helpers;
use App\Models\User;
use App\Notifications\VerifyProofNotification;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\PushNotificationTemplate;
use App\Models\SmsTemplate;

class VerifyProofListener
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(VerifyProofEvent $event)
    {
        try {
            $booking = $event->booking;
            if ($booking) {
                foreach ($booking->sub_bookings as $sub_booking) {
                    if (isset($sub_booking->provider_id)) {
                        $provider = Helpers::getProviderById($sub_booking->provider_id);                       
                        if ($provider) {
                            $this->sendPushNotification($provider->fcm_token, $event, RoleEnum::PROVIDER);
                            $provider->notify(new VerifyProofNotification($event->booking, RoleEnum::PROVIDER));
                            $sendTo = ('+'.$provider?->code.$provider?->phone);
                            Helpers::sendSMS($sendTo, $this->getSMSMessage($event, RoleEnum::PROVIDER));
                        }
                    }
                }

                $admin = User::role(RoleEnum::ADMIN)->first();
                if ($admin) {
                    $this->sendPushNotification($admin->fcm_token, $event, RoleEnum::ADMIN);
                    $admin->notify(new VerifyProofNotification($event->booking, RoleEnum::ADMIN));
                    $sendTo = ('+'.$admin?->code.$admin?->phone);
                    Helpers::sendSMS($sendTo, $this->getSMSMessage($event, RoleEnum::PROVIDER));
                }
            }
        } catch (Exception $e) {
            // Handle exception (e.g., log it)
        }
    }

    public function sendPushNotification($token, $event, $role)
    {
        if ($token) {
            $title = '';
            $body = '';
            $locale = request()->hasHeader('Accept-Language') ? request()->header('Accept-Language') : app()->getLocale();
            
            switch ($role) {
                case RoleEnum::ADMIN:
                    $slug = 'proof-mail-admin';
                    break;
                case RoleEnum::PROVIDER:
                    $slug = 'proof-mail-provider';
                    break;
            }

            $content = PushNotificationTemplate::where('slug', $slug)->first();

            if ($content) {
                $data = [
                    '{{booking_number}}' => $event->booking->booking_number,
                ];

                $title = $content->title[$locale];
                $body = str_replace(array_keys($data), array_values($data), $content->content[$locale]);

            } else {
                $title = "Service Proof Added for Booking #{$event->booking->booking_number}";
                $body = 'Your prompt attention is requested to verify the provided proof.';
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
                        'booking_id' => (string) $event->booking->id,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'booking',
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
                $slug = 'proof-mail-admin';
                break;
            case 'provider':
                $slug = 'proof-mail-provider';
                break;
        }
    
        $content = SmsTemplate::where('slug', $slug)->first();
        if ($content) {
            $data = [
                '{{booking_number}}' => $event->booking?->booking_number,
            ];
            $message = str_replace(array_keys($data), array_values($data), $content?->content[$locale]);
        }  else {
            $message = "Service Proof Added for Booking #{$event->booking->booking_number}, Your prompt attention is requested to verify the provided proof.";
        }
        return $message;
    }
}
