<?php

namespace App\Filament\Resources\AlertContactResource\Pages;

use App\DataTransferObjects\AlertContactDto;
use App\Filament\Resources\AlertContactResource;
use App\Services\AlertContactService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAlertContact extends CreateRecord
{
    protected static string $resource = AlertContactResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data["user_id"] = auth()->id();
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $dto = new AlertContactDto(...$data);

        $service = app(AlertContactService::class);

        return $service->createContact($dto);
    }
}
