<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Jobs\ProcessProductImageJob;

class LogNewProductListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductCreated $event): void
    {
        ProcessProductImageJob::dispatch($event->product);
    }
}
