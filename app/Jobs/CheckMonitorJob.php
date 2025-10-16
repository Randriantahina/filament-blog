<?php

namespace App\Jobs;

use App\Enums\MonitorStatus;
use App\Enums\MonitorType;
use App\Models\Monitor;
use App\Repositories\Contracts\MonitorRepositoryInterface;
use App\Services\CheckLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class CheckMonitorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 5;

    public function __construct(public Monitor $monitor)
    {}

    public function handle(
        MonitorRepositoryInterface $monitorRepository,
        CheckLogService $checkLogService
    ): void {
        if ($this->monitor->type === MonitorType::Heartbeat) {
            // Heartbeat monitors are checked differently, so we skip them here.
            return;
        }

        $checkResult = [
            'is_up' => false,
            'status_code' => null,
            'response_time_ms' => null,
            'error_message' => null,
            'response_body_snippet' => null,
        ];

        $oldStatus = $this->monitor->uptime_status;

        try {
            $checkResult = match ($this->monitor->type) {
                MonitorType::Http => $this->performHttpCheck(),
                MonitorType::Ping => $this->performPingCheck(),
                MonitorType::Port => $this->performPortCheck(),
                MonitorType::Keyword => $this->performKeywordCheck(),
                default => throw new \Exception('Unsupported monitor type'),
            };
        } catch (Throwable $e) {
            $checkResult['error_message'] = $e->getMessage();
        } finally {
            $checkLogService->createLog(
                $this->monitor,
                $checkResult['is_up'],
                $checkResult['status_code'],
                $checkResult['response_time_ms'],
                $checkResult['response_body_snippet'],
                $checkResult['error_message']
            );

            $newStatus = $checkResult['is_up'] ? MonitorStatus::Up : MonitorStatus::Down;

            if ($oldStatus !== $newStatus) {
                $monitorRepository->updateUptimeStatus($this->monitor, $newStatus->value);
                \App\Events\MonitorStatusChanged::dispatch($this->monitor, $oldStatus, $newStatus);
            }
            $monitorRepository->updateLastCheckedAt($this->monitor);
        }
    }

    private function performHttpCheck(): array
    {
        $start = microtime(true);
        $request = Http::withHeaders($this->monitor->headers ?? []);
        $response = $request->{strtolower($this->monitor->method)}($this->monitor->url, $this->monitor->body ? json_decode($this->monitor->body, true) : []);
        $end = microtime(true);

        $isUp = $response->successful();
        $responseBodySnippet = !$isUp ? substr($response->body(), 0, 500) : null;

        return [
            'is_up' => $isUp,
            'status_code' => $response->status(),
            'response_time_ms' => round(($end - $start) * 1000),
            'error_message' => $isUp ? null : $response->reason(),
            'response_body_snippet' => $responseBodySnippet,
        ];
    }

    private function performPingCheck(): array
    {
        $host = parse_url($this->monitor->url, PHP_URL_HOST) ?? $this->monitor->url;
        $command = 'ping -c 1 -W 5 ' . escapeshellarg($host);
        $start = microtime(true);
        exec($command, $output, $return_var);
        $end = microtime(true);

        $isUp = $return_var === 0;

        return [
            'is_up' => $isUp,
            'status_code' => $return_var,
            'response_time_ms' => round(($end - $start) * 1000),
            'error_message' => $isUp ? null : implode("\n", $output),
            'response_body_snippet' => null,
        ];
    }

    private function performPortCheck(): array
    {
        $host = parse_url($this->monitor->url, PHP_URL_HOST) ?? $this->monitor->url;
        $port = $this->monitor->port;
        $timeout = 10;
        $start = microtime(true);
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        $end = microtime(true);

        $isUp = is_resource($connection);
        if ($isUp) {
            fclose($connection);
        }

        return [
            'is_up' => $isUp,
            'status_code' => $isUp ? 200 : $errno,
            'response_time_ms' => round(($end - $start) * 1000),
            'error_message' => $isUp ? null : $errstr,
            'response_body_snippet' => null,
        ];
    }

    private function performKeywordCheck(): array
    {
        // First, perform the HTTP check to ensure the page is accessible.
        $start = microtime(true);
        $request = Http::withHeaders($this->monitor->headers ?? []);
        $response = $request->{strtolower($this->monitor->method)}($this->monitor->url, $this->monitor->body ? json_decode($this->monitor->body, true) : []);
        $end = microtime(true);

        $responseTimeMs = round(($end - $start) * 1000);

        // If the HTTP request itself failed, we can stop here.
        if ($response->failed()) {
            return [
                'is_up' => false,
                'status_code' => $response->status(),
                'response_time_ms' => $responseTimeMs,
                'error_message' => $response->reason(),
                'response_body_snippet' => substr($response->body(), 0, 500),
            ];
        }

        // Now, check for the keyword in the successful response.
        $body = $response->body();
        $keyword = $this->monitor->keyword;

        $found = $this->monitor->keyword_case_sensitive
            ? str_contains($body, $keyword)
            : stripos($body, $keyword) !== false;

        $isUp = $found;
        $errorMessage = $isUp ? null : "Keyword '{$keyword}' not found in response.";
        $responseBodySnippet = !$isUp ? substr($body, 0, 500) : null;

        return [
            'is_up' => $isUp,
            'status_code' => $response->status(),
            'response_time_ms' => $responseTimeMs,
            'error_message' => $errorMessage,
            'response_body_snippet' => $responseBodySnippet,
        ];
    }
}
