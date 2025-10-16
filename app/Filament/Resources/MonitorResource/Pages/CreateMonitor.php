<?php

namespace App\Filament\Resources\MonitorResource\Pages;

use App\DataTransferObjects\MonitorDto;
use App\Filament\Resources\MonitorResource;
use App\Models\Monitor;
use App\Services\MonitorService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMonitor extends CreateRecord
{
    protected static string $resource = MonitorResource::class;

    protected function handleRecordCreation(array $data): Monitor
    {
        $monitorService = app(MonitorService::class);
        $currentUser = auth()->user();
        if (!$currentUser) {
            throw new \Exception("Authenticated user not found.");
        }

        $dto = MonitorDto::from([...$data, "user_id" => $currentUser->id]);

        return $monitorService->createMonitor($dto);
    }
}
