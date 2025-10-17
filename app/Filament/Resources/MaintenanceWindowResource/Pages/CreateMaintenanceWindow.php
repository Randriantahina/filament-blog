<?php

namespace App\Filament\Resources\MaintenanceWindowResource\Pages;

use App\DataTransferObjects\MaintenanceWindowDto;
use App\Filament\Resources\MaintenanceWindowResource;
use App\Services\MaintenanceWindowService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMaintenanceWindow extends CreateRecord
{
    protected static string $resource = MaintenanceWindowResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $dto = new MaintenanceWindowDto(
            name: $data['name'],
            description: $data['description'],
            starts_at: $data['starts_at'],
            ends_at: $data['ends_at'],
            monitors: $data['monitors'] ?? []
        );
        $service = app(MaintenanceWindowService::class);
        return $service->createWindow($dto);
    }
}