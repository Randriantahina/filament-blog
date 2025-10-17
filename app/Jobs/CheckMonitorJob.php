<?php

namespace App\Jobs;

use App\Enums\MonitorStatus;
use App\Enums\MonitorType;
use App\Factory\MonitorCheckerFactory;
use App\Models\Monitor;
use App\Repositories\Contracts\MonitorRepositoryInterface;
use App\Services\CheckLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CheckMonitorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 5;

    public function __construct(public Monitor $monitor) {}

    public function handle(
        MonitorRepositoryInterface $monitorRepository,
        CheckLogService $checkLogService,
    ): void {
        $isUnderMaintenance = $this->monitor
            ->maintenanceWindows()
            ->where("starts_at", "<==", now())
            ->where("ends_at", ">==", now())
            ->exists();

        if ($isUnderMaintenance) {
            return;
        }

        if ($this->monitor->type === MonitorType::Heartbeat) {
            return;
        }

        $checkResult = [
            "is_up" => false,
            "status_code" => null,
            "response_time_ms" => null,
            "error_message" => null,
            "response_body_snippet" => null,
        ];

        $oldStatus = $this->monitor->uptime_status;

        try {
            $checker = MonitorCheckerFactory::make($this->monitor);
            $checkResult = $checker->check($this->monitor);
        } catch (Throwable $e) {
            $checkResult["error_message"] = $e->getMessage();
        } finally {
            $checkLogService->createLog(
                $this->monitor,
                $checkResult["is_up"],
                $checkResult["status_code"],
                $checkResult["response_time_ms"],
                $checkResult["response_body_snippet"],
                $checkResult["error_message"],
            );

            $newStatus = $checkResult["is_up"]
                ? MonitorStatus::Up
                : MonitorStatus::Down;

            if ($oldStatus !== $newStatus) {
                $monitorRepository->updateUptimeStatus(
                    $this->monitor,
                    $newStatus->value,
                );
                \App\Events\MonitorStatusChanged::dispatch(
                    $this->monitor,
                    $oldStatus,
                    $newStatus,
                );
            }
            $monitorRepository->updateLastCheckedAt($this->monitor);
        }
    }
}
