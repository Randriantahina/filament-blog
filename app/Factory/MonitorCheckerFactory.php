<?php

namespace App\Factory;

use App\Enums\MonitorType;
use App\Models\Monitor;
use App\Services\MonitorCheckersService\HttpMonitorCheckerService;
use App\Services\MonitorCheckersService\KeywordMonitorCheckerService;
use App\Services\MonitorCheckersService\Contracts\MonitorCheckerInterface;
use App\Services\MonitorCheckersService\PingMonitorCheckerService;
use App\Services\MonitorCheckersService\PortMonitorCheckerService;
use InvalidArgumentException;

class MonitorCheckerFactory
{
    /**
     * A map of monitor types to their corresponding checker classes.
     */
    protected static array $checkerMap = [
        MonitorType::Http->value => HttpMonitorCheckerService::class,
        MonitorType::Ping->value => PingMonitorCheckerService::class,
        MonitorType::Port->value => PortMonitorCheckerService::class,
        MonitorType::Keyword->value => KeywordMonitorCheckerService::class,
    ];

    /**
     * Create a checker instance for the given monitor.
     *
     * @param Monitor $monitor
     * @return MonitorCheckerInterface
     */
    public static function make(Monitor $monitor): MonitorCheckerInterface
    {
        $checkerClass = self::$checkerMap[$monitor->type->value] ?? null;

        if (!$checkerClass) {
            throw new InvalidArgumentException(
                "Unsupported monitor type: {$monitor->type->value}",
            );
        }

        return new $checkerClass();
    }
}
