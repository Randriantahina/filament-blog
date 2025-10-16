<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MonitorType: string implements HasLabel
{
    case Http = 'http';
    case Ping = 'ping';
    case Port = 'port';
    case Keyword = 'keyword';
    case Heartbeat = 'heartbeat';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Http => 'HTTP(s)',
            self::Ping => 'Ping',
            self::Port => 'Port',
            self::Keyword => 'Keyword',
            self::Heartbeat => 'Heartbeat',
        };
    }
}
