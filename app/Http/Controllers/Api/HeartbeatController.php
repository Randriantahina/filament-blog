<?php

namespace App\Http\Controllers\Api;

use App\Enums\MonitorStatus;
use App\Events\MonitorStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\Monitor;
use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Monitor $monitor)
    {
        $oldStatus = $monitor->uptime_status;

        $monitor->update([
            'last_checked_at' => now(),
            'uptime_status' => MonitorStatus::Up,
        ]);

        if ($oldStatus === MonitorStatus::Down) {
            MonitorStatusChanged::dispatch($monitor, $oldStatus, MonitorStatus::Up);
        }

        return response()->json(['message' => 'OK']);
    }
}
