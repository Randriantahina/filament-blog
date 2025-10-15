<?php

namespace App\Services;

use App\DataTransferObjects\ProductDto;
use App\Events\ProductCreated;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    public function createProduct(ProductDto $dto): Product
    {
        $product = $this->productRepository->create($dto);

        ProductCreated::dispatch($product);

        return $product;
    }
}