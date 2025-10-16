<?php

namespace App\DataTransferObjects;

use App\Enums\MonitorStatus;
use App\Enums\MonitorType;
use Spatie\LaravelData\Data;

class MonitorDto extends Data
{
    public function __construct(
        public int $user_id,
        public string $name,
        public MonitorType $type,
        public MonitorStatus $uptimeStatus = MonitorStatus::Up,
        public int $checkIntervalMinutes = 5,
        public ?string $url = null,
        public string $method = "GET",
        public ?string $body = null,
        public ?array $headers = null,
        public ?int $port = null,
        public ?string $keyword = null,
        public bool $keyword_case_sensitive = false,
        public int $heartbeat_grace_period_in_minutes = 5,
        public ?\DateTime $lastCheckedAt = null,
    ) {}
}
