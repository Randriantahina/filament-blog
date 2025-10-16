<?php

namespace App\Console\Commands;

use App\Jobs\CheckMonitorJob; // Will create this next
use App\Repositories\Contracts\MonitorRepositoryInterface;
use Illuminate\Console\Command;

class DispatchMonitorChecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitors:dispatch-checks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to check the status of active monitors.';

    public function __construct(
        protected MonitorRepositoryInterface $monitorRepository
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $monitors = $this->monitorRepository->getMonitorsDueForCheck();

        foreach ($monitors as $monitor) {
            CheckMonitorJob::dispatch($monitor);
            $this->info("Dispatched check for monitor: {$monitor->name} ({$monitor->url})");
        }

        $this->info('Monitor check dispatch completed.');
    }
}
