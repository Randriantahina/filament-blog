<?php

namespace App\Events;

use App\Enums\MonitorStatus;
use App\Models\Monitor;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MonitorStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Monitor $monitor,
        public MonitorStatus $oldStatus,
        public MonitorStatus $newStatus
    ) {}
}
