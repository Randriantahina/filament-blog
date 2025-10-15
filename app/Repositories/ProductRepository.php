<?php

namespace App\Repositories;

use App\DataTransferObjects\ProductDto;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(ProductDto $dto): Product
    {
        return Product::create([
            'name' => $dto->name,
            'slug' => $dto->slug,
            'price' => $dto->price,
            'sku' => $dto->sku,
            'description' => $dto->description,
        ]);
    }
}
