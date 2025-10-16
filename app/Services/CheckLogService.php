<?php

namespace App\Services;

use App\Models\CheckLog;
use App\Models\Monitor;

class CheckLogService
{
    public function createLog(
        Monitor $monitor,
        bool $isUp,
        ?int $statusCode = null,
        ?int $responseTimeMs = null,
        ?string $responseBodySnippet = null,
        ?string $errorMessage = null
    ): CheckLog {
        return $monitor->checkLogs()->create([
            'is_up' => $isUp,
            'status_code' => $statusCode,
            'response_time_ms' => $responseTimeMs,
            'response_body_snippet' => $responseBodySnippet,
            'error_message' => $errorMessage,
        ]);
    }
}
