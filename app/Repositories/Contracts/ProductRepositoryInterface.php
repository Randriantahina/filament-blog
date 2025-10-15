<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\ProductDto;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function create(ProductDto $dto): Product;
}
