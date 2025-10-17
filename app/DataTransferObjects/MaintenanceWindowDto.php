<?php

namespace App\DataTransferObjects;

class MaintenanceWindowDto
{
    /**
     * @param array<int> $monitors
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $starts_at,
        public readonly string $ends_at,
        public readonly array $monitors,
    ) {}
}
