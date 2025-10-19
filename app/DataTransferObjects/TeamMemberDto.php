<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class TeamMemberDto extends Data
{
    public function __construct(
        public int $userId,
        public string $role,
    ) {}
}
