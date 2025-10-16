<?php

namespace App\Listeners;

use App\Events\MonitorStatusChanged;
use App\Notifications\MonitorStatusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        $event->monitor->user->notify(
            new MonitorStatusChangedNotification(
                $event->monitor,
                $event->oldStatus,
                $event->newStatus
            )
        );
    }
}
