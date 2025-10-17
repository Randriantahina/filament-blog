<?php

namespace App\Services;

use App\DataTransferObjects\MaintenanceWindowDto;
use App\Models\MaintenanceWindow;
use App\Repositories\Contracts\MaintenanceWindowRepositoryInterface;

class MaintenanceWindowService
{
    public function __construct(
        protected MaintenanceWindowRepositoryInterface $repository
    ) {}

    public function createWindow(MaintenanceWindowDto $dto): MaintenanceWindow
    {
        return $this->repository->create((array) $dto);
    }

    public function updateWindow(int $id, array $data): MaintenanceWindow
    {
        return $this->repository->update($id, $data);
    }

    public function isMonitorUnderMaintenance(int $monitorId): bool
    {
        return $this->repository->isMonitorUnderMaintenance($monitorId);
    }
}
