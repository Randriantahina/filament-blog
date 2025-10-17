<?php

namespace App\Filament\Resources\AlertContactResource\Pages;

use App\Filament\Resources\AlertContactResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAlertContact extends CreateRecord
{
    protected static string $resource = AlertContactResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data["user_id"] = auth()->id();

        return $data;
    }
}
