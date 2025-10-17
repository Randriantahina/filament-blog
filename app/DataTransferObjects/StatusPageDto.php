<?php

namespace App\DataTransferObjects;

class StatusPageDto
{
    /**
     * @param array<int> $monitors
     */
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
        public readonly int $user_id,
        public readonly ?string $description,
        public readonly array $monitors,
    ) {}
}
