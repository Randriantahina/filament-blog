<?php

namespace App\Services;

use App\DataTransferObjects\MonitorDto;
use App\Models\Monitor;
use App\Repositories\Contracts\MonitorRepositoryInterface;

class MonitorService
{
    public function __construct(
        protected MonitorRepositoryInterface $monitorRepository
    ) {}

    public function createMonitor(MonitorDto $dto): Monitor
    {
        return $this->monitorRepository->create($dto->toArray());
    }

    public function updateMonitor(int $id, MonitorDto $dto): ?Monitor
    {
        $this->monitorRepository->update($id, $dto->toArray());
        return $this->monitorRepository->find($id);
    }

    public function deleteMonitor(int $id): bool
    {
        return $this->monitorRepository->delete($id);
    }

    public function getMonitor(int $id): ?Monitor
    {
        return $this->monitorRepository->find($id);
    }

    public function getAllMonitors(): \Illuminate\Support\Collection
    {
        return $this->monitorRepository->all();
    }
}
