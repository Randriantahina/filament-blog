<?php

namespace App\Filament\Resources\StatusPageResource\Pages;

use App\DataTransferObjects\StatusPageDto;
use App\Filament\Resources\StatusPageResource;
use App\Services\StatusPageService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStatusPage extends CreateRecord
{
    protected static string $resource = StatusPageResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $dto = new StatusPageDto(
            name: $data['name'],
            slug: $data['slug'],
            user_id: $data['user_id'],
            description: $data['description'] ?? null,
            monitors: $data['monitors'] ?? []
        );

        $service = app(StatusPageService::class);
        return $service->createPage($dto);
    }
}