<?php
namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class AlertContactDto extends Data
{
    public function __construct(
        public string $name,
        public string $type,
        public string $value,
        public int $user_id,
    ) {}
}
