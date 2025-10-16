<?php

namespace App\Repositories\Contracts;

use App\Models\Monitor;
use Illuminate\Support\Collection;

interface MonitorRepositoryInterface
{
    public function create(array $data): Monitor;
    public function find(int $id): ?Monitor;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function all(): Collection;
    public function getMonitorsDueForCheck(): Collection;
    public function updateUptimeStatus(Monitor $monitor, string $status): bool;
    public function updateLastCheckedAt(Monitor $monitor): bool;
}
