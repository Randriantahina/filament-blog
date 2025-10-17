<?php

namespace App\Repositories\Contracts;

use App\Models\MaintenanceWindow;

interface MaintenanceWindowRepositoryInterface
{
    public function create(array $data): MaintenanceWindow;
    public function update(int $id, array $data): MaintenanceWindow;
    public function isMonitorUnderMaintenance(int $monitorId): bool;
}
