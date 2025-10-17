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
        $monitors = Monitor::where(
            "uptime_status",
            "!=",
            \App\Enums\MonitorStatus::Paused->value,
        )->get();

        return $monitors->filter(function ($monitor) {
            if (is_null($monitor->last_checked_at)) {
                return true;
            }

            return $monitor->last_checked_at
                ->addMinutes($monitor->check_interval_minutes)
                ->isPast();
        });
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
