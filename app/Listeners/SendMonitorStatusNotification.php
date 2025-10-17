<?php

namespace App\Listeners;

use App\Events\MonitorStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MonitorStatusChangedNotification;

class SendMonitorStatusNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MonitorStatusChanged $event): void
    {
        //on recupere tous les contacts associés au moniteur
        $contacts = $event->monitor->alertContacts()->get();

        //s'il n'y a pas de contact, on ne fait rien
        if ($contacts->isEmpty()) {
            return;
        }

        Notification::send(
            $contacts,
            new MonitorStatusChangedNotification(
                $event->monitor,
                $event->oldStatus,
                $event->newStatus,
            ),
        );
    }
}
