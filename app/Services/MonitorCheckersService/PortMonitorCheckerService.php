<?php

namespace App\Services\MonitorCheckersService;
use App\Models\Monitor;
use App\Services\MonitorCheckersService\Contracts\MonitorCheckerInterface;

class PortMonitorCheckerService implements MonitorCheckerInterface
{
    public function check(Monitor $monitor): array
    {
        $host = parse_url($monitor->url, PHP_URL_HOST) ?? $monitor->url;
        $port = $monitor->port;
        $timeout = 10;
        $start = microtime(true);
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        $end = microtime(true);

        $isUp = is_resource($connection);
        if ($isUp) {
            fclose($connection);
        }

        return [
            "is_up" => $isUp,
            "status_code" => $isUp ? 200 : $errno,
            "response_time_ms" => round(($end - $start) * 1000),
            "error_message" => $isUp ? null : $errstr,
            "response_body_snippet" => null,
        ];
    }
}
