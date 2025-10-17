<?php

namespace App\Services\MonitorCheckersService;

use App\Models\Monitor;
use App\Services\MonitorCheckersService\Contracts\MonitorCheckerInterface;

class PingMonitorCheckerService implements MonitorCheckerInterface
{
    public function check(Monitor $monitor): array
    {
        $host = parse_url($monitor->url, PHP_URL_HOST) ?? $monitor->url;
        $command = "ping -c 1 -W 5 " . escapeshellarg($host);
        $start = microtime(true);
        exec($command, $output, $return_var);
        $end = microtime(true);

        $isUp = $return_var === 0;

        return [
            "is_up" => $isUp,
            "status_code" => $return_var,
            "response_time_ms" => round(($end - $start) * 1000),
            "error_message" => $isUp ? null : implode("\n", $output),
            "response_body_snippet" => null,
        ];
    }
}
