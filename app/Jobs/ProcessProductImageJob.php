<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessProductImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly Product $product
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simulate image processing
        Log::info('Processing image for product: ' . $this->product->name);
        sleep(5); // Simulate a long task
        Log::info('Finished processing image for product: ' . $this->product->name);
    }
}