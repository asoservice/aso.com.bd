<?php

namespace App\Listeners;

use App\Enums\RoleEnum;
use App\Events\CreateServiceEvent;
use App\Helpers\Helpers;
use App\Models\User;
use App\Notifications\CreateProviderNotification;
use Exception;

class CreateServiceListener
{
    /**
     * Handle the event.
     */
    public function handle(CreateServiceEvent $event)
    {
        try {
            $admin = User::role(RoleEnum::ADMIN)->first();
            $users_mail = User::role(RoleEnum::CONSUMER)->get();
            $users = User::whereNotNull('fcm_token')->role(RoleEnum::CONSUMER)->pluck('fcm_token')->all();
            foreach ($users as $token) {
                $notification = [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $event->service->name.'The new service is listed',
                            'body' => '',
                            'image' => '',
                        ],
                        'data' => [
                            'service' => (string) $event->service->name,
                            'service_id' => (string) $event?->service?->id,
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'type' => 'service',
                            'image' => $event->service->getFirstMediaUrl('image'),
                        ],
                    ],
                ];

                Helpers::pushNotification($notification);
            }

            if (isset($admin)) {
                $admin->notify(new CreateProviderNotification($event->service));
            }
            if (isset($users_mail)) {
                foreach ($users_mail as $user) {
                    $user->notify(new CreateProviderNotification($event->service));
                }
            }
        } catch (Exception $e) {
        }
    }
}
