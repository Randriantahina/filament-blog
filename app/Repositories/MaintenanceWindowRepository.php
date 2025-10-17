<?php

namespace App\Repositories;

use App\Models\MaintenanceWindow;
use App\Models\Monitor;
use App\Repositories\Contracts\MaintenanceWindowRepositoryInterface;
use Illuminate\Support\Arr;

class MaintenanceWindowRepository implements MaintenanceWindowRepositoryInterface
{
    public function create(array $data): MaintenanceWindow
    {
        $maintenance = MaintenanceWindow::create(Arr::except($data, 'monitors'));
        $maintenance->monitors()->sync($data['monitors']);
        return $maintenance;
    }

    public function update(int $id, array $data): MaintenanceWindow
    {
        $maintenance = MaintenanceWindow::findOrFail($id);
        $maintenance->update(Arr::except($data, 'monitors'));
        if (isset($data['monitors'])) {
            $maintenance->monitors()->sync($data['monitors']);
        }
        return $maintenance;
    }

    public function isMonitorUnderMaintenance(int $monitorId): bool
    {
        $monitor = Monitor::findOrFail($monitorId);
        return $monitor->maintenanceWindows()
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->exists();
    }
}
