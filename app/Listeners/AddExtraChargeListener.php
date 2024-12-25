<?php

namespace App\Listeners;

use App\Enums\RoleEnum;
use App\Events\AddExtraChargeEvent;
use App\Helpers\Helpers;
use App\Models\User;
use App\Notifications\AddExtraChargeNotification;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\PushNotificationTemplate;

class AddExtraChargeListener
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(AddExtraChargeEvent $event)
    {
        try {

            $booking_id = $event->extraCharge->booking_id;
            $booking = Helpers::getBookingById($booking_id);
            if ($booking) {
                $consumer = Helpers::getProviderById($booking->consumer_id);
                if (isset($consumer) && is_null($booking?->parent_id)) {
                    $consumer->notify(new AddExtraChargeNotification($event->extraCharge, RoleEnum::CONSUMER));
                    $sendTo = ('+'.$consumer?->code.$consumer?->phone);
                    Helpers::sendSMS($sendTo, $this->getSMSMessage($event));
                }

                if ($booking->parent_id) {
                    if (isset($booking->provider_id)) {
                        $provider = Helpers::getProviderById($booking->provider_id);
                        $this->sendPushNotification($provider?->fcm_token, $event);
                        $provider->notify(new AddExtraChargeNotification($event->extraCharge, RoleEnum::PROVIDER));
                        $sendTo = ('+'.$consumer?->code.$provider?->phone);
                        Helpers::sendSMS($sendTo, $this->getSMSMessage($event));
                    }
                } else {
                    foreach ($booking->sub_bookings as $sub_booking) {
                        if (isset($sub_booking->provider_id)) {
                            $provider = Helpers::getProviderById($sub_booking->provider_id);
                            $this->sendPushNotification($provider?->fcm_token, $event);
                            $provider->notify(new AddExtraChargeNotification($event->extraCharge, RoleEnum::PROVIDER));
                            $sendTo = ('+'.$provider?->code.$provider?->phone);
                            Helpers::sendSMS($sendTo, $this->getSMSMessage($event));
                        }
                    }
                }

                $admin = User::role(RoleEnum::ADMIN)->first();
                if (isset($admin)) {
                    $this->sendPushNotification($admin?->fcm_token, $event);
                    $admin->notify(new AddExtraChargeNotification($event->extraCharge, RoleEnum::ADMIN));
                    $sendTo = ('+'.$admin?->code.$admin?->phone);
                    Helpers::sendSMS($sendTo, $this->getSMSMessage($event));
                }
            }

        } catch (Exception $e) {
            //
        }
    }

    public function sendPushNotification($token, $event)
    {
        $booking_id = $event->extraCharge->booking_id;
        $booking = Helpers::getBookingById($booking_id);

        $content = PushNotificationTemplate::where('slug', 'add-extra-charge-admin')->first();

        if ($token) {
            $locale = request()->hasHeader('Accept-Language') ? 
                request()->header('Accept-Language') : 
                app()->getLocale();

            $data = [
                '{{booking_number}}' => $booking?->booking_number,
                '{{total}}' => $event?->extraCharge?->total,
                '{{per_service_amount}}' => $event?->extraCharge?->per_service_amount,
                '{{company_name}}' => config('app.name'),
            ];

            $title = "An Extra Charge added on booking #{$booking?->booking_number}";
            $body = "{$event?->extraCharge?->total} total amount added per service amount is {$event?->extraCharge?->per_service_amount}";

            if ($content) {
                $pushNotificationContent = str_replace(array_keys($data), array_values($data), $content->content[$locale]);
                $title = $content->title[$locale];
                $body = $pushNotificationContent;
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
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'booking',
                        'booking_id' => (string) $event?->extraCharge?->booking_id,
                    ],
                ],
            ];

            Helpers::pushNotification($notification);
        }
    }



    public function getSMSMessage($event)
    {
        $booking_id = $event->extraCharge->booking_id;
        $booking = Helpers::getBookingById($booking_id);
        return "An Extra Charge added on booking #{$booking?->booking_number}";
    }
}
