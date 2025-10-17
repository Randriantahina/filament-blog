<?php

namespace App\Filament\Resources\StatusPageResource\Pages;

use App\Filament\Resources\StatusPageResource;
use App\Services\StatusPageService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditStatusPage extends EditRecord
{
    protected static string $resource = StatusPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $service = app(StatusPageService::class);
        return $service->updatePage($record->id, $data);
    }
}