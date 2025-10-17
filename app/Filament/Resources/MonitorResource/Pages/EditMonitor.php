<?php

namespace App\Filament\Resources\MonitorResource\Pages;

use App\DataTransferObjects\MonitorDto;
use App\Filament\Resources\MonitorResource;
use App\Models\Monitor;
use App\Services\MonitorService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonitor extends EditRecord
{
    protected static string $resource = MonitorResource::class;

    protected function handleRecordUpdate(
        \Illuminate\Database\Eloquent\Model $record,
        array $data,
    ): \Illuminate\Database\Eloquent\Model {
        $monitorService = app(MonitorService::class);
        $currentUser = auth()->user();
        if (!$currentUser) {
            throw new \Exception("Authenticated user not found.");
        }

        unset($data["user_id"]);

        return $monitorService->updateMonitor($record->id, $data);
    }

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
