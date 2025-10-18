<?php

namespace App\Services\NotificationChannels;

use App\Models\AlertContact;
use App\Models\Monitor;
use App\Services\NotificationChannels\Contracts\NotificationChannelInterface;
use Illuminate\Support\Facades\Http;

class WebhookChannel implements NotificationChannelInterface
{
    public function send(AlertContact $alertContact, Monitor $monitor): void
    {
        Http::post($alertContact->value, [
            'monitor_name' => $monitor->name,
            'status' => $monitor->status->name,
            'url' => $monitor->url,
            "message" => "Monitor '{$monitor->name}' is now {" . $monitor->status->name . "}",
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
