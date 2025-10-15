<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\DataTransferObjects\ProductDto;
use App\Filament\Resources\ProductResource;
use App\Services\ProductService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var ProductService $productService */
        $productService = app(ProductService::class);

        $dto = new ProductDto(
            name: $data['name'],
            slug: $data['slug'],
            price: (float)$data['price'],
            sku: $data['sku'],
            description: $data['description'] ?? null
        );

        return $productService->createProduct($dto);
    }
}