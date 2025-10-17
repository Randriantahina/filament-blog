<?php

namespace App\Services\MonitorCheckersService;

use App\Models\Monitor;
use Illuminate\Support\Facades\Http;
use App\Services\MonitorCheckersService\Contracts\MonitorCheckerInterface;

class KeywordMonitorCheckerService implements MonitorCheckerInterface
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

        $responseTimeMs = round(($end - $start) * 1000);

        if ($response->failed()) {
            return [
                "is_up" => false,
                "status_code" => $response->status(),
                "response_time_ms" => $responseTimeMs,
                "error_message" => $response->reason(),
                "response_body_snippet" => substr($response->body(), 0, 500),
            ];
        }

        $body = $response->body();
        $keyword = $monitor->keyword;

        $found = $monitor->keyword_case_sensitive
            ? str_contains($body, $keyword)
            : stripos($body, $keyword) !== false;

        $isUp = $found;
        $errorMessage = $isUp
            ? null
            : "Keyword '{$keyword}' not found in response.";
        $responseBodySnippet = !$isUp ? substr($body, 0, 500) : null;

        return [
            "is_up" => $isUp,
            "status_code" => $response->status(),
            "response_time_ms" => $responseTimeMs,
            "error_message" => $errorMessage,
            "response_body_snippet" => $responseBodySnippet,
        ];
    }
}
