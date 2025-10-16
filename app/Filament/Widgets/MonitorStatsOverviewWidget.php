<?php

namespace App\Filament\Widgets;

use App\Enums\MonitorStatus;
use App\Repositories\Contracts\MonitorRepositoryInterface;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MonitorStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $monitorRepository = app(MonitorRepositoryInterface::class);

        $totalMonitors = $monitorRepository->all()->count();
        $upMonitors = $monitorRepository
            ->all()
            ->where("uptime_status", MonitorStatus::Up)
            ->count();
        $downMonitors = $monitorRepository
            ->all()
            ->where("uptime_status", MonitorStatus::Down)
            ->count();
        $pausedMonitors = $monitorRepository
            ->all()
            ->where("uptime_status", MonitorStatus::Paused)
            ->count();

        return [
            Stat::make("Total Monitors", $totalMonitors)
                ->description("All registered monitors")
                ->color("primary"),
            Stat::make("Monitors Up", $upMonitors)
                ->description("Currently online")
                ->color("success"),
            Stat::make("Monitors Down", $downMonitors)
                ->description("Currently offline")
                ->color("danger"),
            Stat::make("Monitors Paused", $pausedMonitors)
                ->description("Not being checked")
                ->color("warning"),
        ];
    }
}
