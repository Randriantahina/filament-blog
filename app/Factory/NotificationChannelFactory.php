<?php

namespace App\Factory;

use App\Enums\AlertContactType;
use App\Models\AlertContact;
use App\Services\NotificationChannels\Contracts\NotificationChannelInterface;
use App\Services\NotificationChannels\EmailChannel;
use App\Services\NotificationChannels\WebhookChannel;
use InvalidArgumentException;

class NotificationChannelFactory
{
    public function make(AlertContact $alertContact): NotificationChannelInterface
    {
        return match ($alertContact->type) {
            AlertContactType::Email => new EmailChannel(),
            AlertContactType::Webhook => new WebhookChannel(),
            default => throw new InvalidArgumentException('Unsupported notification channel type.'),
        };
    }
}
