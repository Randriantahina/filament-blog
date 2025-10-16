<?php

namespace App\Filament\Widgets;

use App\Models\CheckLog;
use App\Enums\MonitorStatus;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonitorChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Monitor Status (Last 24 Hours)';

    protected function getData(): array
    {
        $data = CheckLog::query()
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($log) {
                return Carbon::parse($log->created_at)->format('H');
            });

        $labels = [];
        $upData = [];
        $downData = [];

        for ($i = 0; $i < 24; $i++) {
            $hour = now()->subHours(23 - $i)->format('H');
            $labels[] = $hour . ':00';
            $upData[] = $data->get($hour)?->where('status', MonitorStatus::Up)->count() ?? 0;
            $downData[] = $data->get($hour)?->where('status', MonitorStatus::Down)->count() ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Monitors Up',
                    'data' => $upData,
                    'borderColor' => '#22c55e',
                ],
                [
                    'label' => 'Monitors Down',
                    'data' => $downData,
                    'borderColor' => '#ef4444',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
