<?php

namespace App\Filament\Resources\MaintenanceWindowResource\Pages;

use App\Filament\Resources\MaintenanceWindowResource;
use App\Services\MaintenanceWindowService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMaintenanceWindow extends EditRecord
{
    protected static string $resource = MaintenanceWindowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $service = app(MaintenanceWindowService::class);
        return $service->updateWindow($record->id, $data);
    }
}