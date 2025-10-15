<?php

namespace App\DataTransferObjects;

class ProductDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
        public readonly float $price,
        public readonly string $sku,
        public readonly ?string $description = null,
    ) {
    }
}
