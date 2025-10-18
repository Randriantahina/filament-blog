<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\MonitorDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreMonitorRequest;
use App\Http\Requests\Api\UpdateMonitorRequest;
use App\Http\Resources\MonitorResource;
use App\Models\Monitor;
use App\Repositories\Contracts\MonitorRepositoryInterface;
use App\Services\MonitorService;

class MonitorController extends Controller
{
    public function __construct(
        private readonly MonitorRepositoryInterface $monitorRepository,
        private readonly MonitorService $monitorService
    ) {}

    public function index()
    {
        return MonitorResource::collection($this->monitorRepository->all());
    }

    public function store(StoreMonitorRequest $request)
    {
        $dto = MonitorDto::from(array_merge($request->validated(), [
            'user_id' => $request->user()->id,
        ]));

        $monitor = $this->monitorService->createMonitor($dto);

        return new MonitorResource($monitor);
    }

    public function show(Monitor $monitor)
    {
        return new MonitorResource($monitor);
    }

    public function update(UpdateMonitorRequest $request, Monitor $monitor)
    {
        $updatedMonitor = $this->monitorService->updateMonitor($monitor->id, $request->validated());

        return new MonitorResource($updatedMonitor);
    }

    public function destroy(Monitor $monitor)
    {
        $this->monitorService->deleteMonitor($monitor->id);

        return response()->noContent();
    }
}
