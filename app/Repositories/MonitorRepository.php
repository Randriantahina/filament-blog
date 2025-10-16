<?php

namespace App\Repositories;

use App\Models\Monitor;
use App\Repositories\Contracts\MonitorRepositoryInterface;
use Illuminate\Support\Collection;

class MonitorRepository implements MonitorRepositoryInterface
{
    public function create(array $data): Monitor
    {
        return Monitor::create($data);
    }

    public function find(int $id): ?Monitor
    {
        return Monitor::find($id);
    }

    public function update(int $id, array $data): bool
    {
        $monitor = $this->find($id);
        if ($monitor) {
            return $monitor->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $monitor = $this->find($id);
        if ($monitor) {
            return $monitor->delete();
        }
        return false;
    }

    public function all(): Collection
    {
        return Monitor::all();
    }

    public function getMonitorsDueForCheck(): Collection
    {
        // This will be refined later when we implement the scheduler and job
        // For now, it returns all monitors that are not paused.
        return Monitor::where('uptime_status', '!=', \App\Enums\MonitorStatus::Paused->value)
                      ->where(function ($query) {
                          $query->whereNull('last_checked_at')
                                ->orWhereRaw('last_checked_at <= DATE_SUB(NOW(), INTERVAL check_interval_minutes MINUTE)');
                      })
                      ->get();
    }

    public function updateUptimeStatus(Monitor $monitor, string $status): bool
    {
        $monitor->uptime_status = $status;
        return $monitor->save();
    }

    public function updateLastCheckedAt(Monitor $monitor): bool
    {
        $monitor->last_checked_at = now();
        return $monitor->save();
    }
}
