<?php

namespace App\Services\MonitorCheckersService\Contracts;
use App\Models\Monitor;

interface MonitorCheckerInterface
{
    public function check(Monitor $monitor): array;
}
