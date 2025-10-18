<?php

namespace App\Enums;

enum AlertContactType: string
{
    case Email = 'email';
    case Webhook = 'webhook';
}
