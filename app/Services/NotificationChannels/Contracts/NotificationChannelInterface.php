<?php

namespace App\Services\NotificationChannels\Contracts;

use App\Models\AlertContact;
use App\Models\Monitor;

interface NotificationChannelInterface
{
    public function send(AlertContact $alertContact, Monitor $monitor): void;
}
