<?php

namespace App\Listeners;

use App\Enums\BidStatusEnum;
use App\Enums\RoleEnum;
use App\Events\AssignBookingEvent;
use App\Helpers\Helpers;
use App\Models\User;
use App\Notifications\AssignBookingNotification;

class AssignBookingListener
{
    /**
     * Handle the event.
     */
    public function handle(AssignBookingEvent $event)
    {
        $serviceMen = $event->servicemen()->get();
    
        if ($serviceMen) {
            foreach ($serviceMen as $serviceman) {
                $serviceman->notify(new AssignBookingNotification($event->booking, $serviceMen));
            }
        }
    }
}
