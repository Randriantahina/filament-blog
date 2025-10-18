<?php

namespace App\Services\NotificationChannels;

use App\Models\AlertContact;
use App\Models\Monitor;
use App\Notifications\MonitorStatusChangedNotification;
use App\Services\NotificationChannels\Contracts\NotificationChannelInterface;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

class EmailChannel implements NotificationChannelInterface
{
    public function send(AlertContact $alertContact, Monitor $monitor): void
    {
        Notification::route('mail', $alertContact->value)
            ->notify(new MonitorStatusChangedNotification($monitor));
    }
}
