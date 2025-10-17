<?php

namespace App\Filament\Resources\AlertContactResource\Pages;

use App\Filament\Resources\AlertContactResource;
use App\Services\AlertContactService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAlertContact extends EditRecord
{
    protected static string $resource = AlertContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->action(function (Model $record) {
                $service = app(AlertContactService::class);
                if ($service->deleteContact($record->id)) {
                    $this->redirect(static::getResource()::getUrl("index"));
                }
            }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $service = app(AlertContactService::class);
        return $service->updateContact($record->id, $data);
    }
}
