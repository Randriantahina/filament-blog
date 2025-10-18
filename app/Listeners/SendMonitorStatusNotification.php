<?php

namespace App\Listeners;

use App\Events\MonitorStatusChanged;
use App\Factory\NotificationChannelFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMonitorStatusNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(private readonly NotificationChannelFactory $factory)
    {
    }

    public function handle(MonitorStatusChanged $event): void
    {
        $monitor = $event->monitor;

        if ($monitor->alertContacts->isEmpty()) {
            return;
        }

        foreach ($monitor->alertContacts as $contact) {
            $channel = $this->factory->make($contact);
            $channel->send($contact, $monitor);
        }
    }
}
