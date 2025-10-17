<?php

namespace App\Services\MonitorCheckersService;

use App\Models\Monitor;
use Illuminate\Support\Facades\Http;
use App\Services\MonitorCheckersService\Contracts\MonitorCheckerInterface;

class HttpMonitorCheckerService implements MonitorCheckerInterface
{
    public function check(Monitor $monitor): array
    {
        $start = microtime(true);
        $request = Http::withHeaders($monitor->headers ?? []);
        $response = $request->{strtolower($monitor->method)}(
            $monitor->url,
            $monitor->body ? json_decode($monitor->body, true) : [],
        );
        $end = microtime(true);

        $isUp = $response->successful();
        $responseBodySnippet = !$isUp
            ? substr($response->body(), 0, 500)
            : null;

        return [
            "is_up" => $isUp,
            "status_code" => $response->status(),
            "response_time_ms" => round(($end - $start) * 1000),
            "error_message" => $isUp ? null : $response->reason(),
            "response_body_snippet" => $responseBodySnippet,
        ];
    }
}
