<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Page</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 text-gray-800">
    @php
        $overallStatus = $statusPage->monitors->every(fn($monitor) => $monitor->uptime_status === \App\Enums\MonitorStatus::Up);
    @endphp
    <div class="container mx-auto px-4 py-10">

        <!-- Header -->
        <header class="mb-12 text-center">
            <h1 class="text-5xl font-bold tracking-tight mb-2">{{ $statusPage->name }}</h1>
            <p class="text-lg text-gray-500">{{ $statusPage->description }}</p>
        </header>

        <!-- Overall Status -->
        <div class="mb-10 p-6 rounded-lg shadow-md {{ $overallStatus ? 'bg-green-100 text-green-900' : 'bg-red-100 text-red-900' }}">
            <div class="flex items-center">
                <svg class="h-8 w-8 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if ($overallStatus)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @endif
                </svg>
                <div class="flex-grow">
                    <h2 class="text-2xl font-semibold">{{ $overallStatus ? 'All systems operational' : 'Some systems are experiencing issues' }}</h2>
                </div>
            </div>
        </div>

        <!-- Monitors List -->
        <div class="space-y-6">
            @foreach ($statusPage->monitors as $monitor)
                @php
                    $latestLog = $monitor->checkLogs->last();
                @endphp
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <!-- Monitor Name and URL -->
                        <div class="flex-grow mb-4 md:mb-0">
                            <p class="text-xl font-semibold">{{ $monitor->name }}</p>
                            <a href="{{ $monitor->url }}" target="_blank" class="text-sm text-gray-400 hover:text-blue-500 transition-colors">{{ $monitor->url }}</a>
                        </div>

                        <!-- Status and Latency -->
                        <div class="flex items-center space-x-6">
                            @if ($monitor->uptime_status === \App\Enums\MonitorStatus::Up)
                                <div class="text-right">
                                    <div class="flex items-center justify-end text-green-500">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        <span class="ml-2 font-bold text-lg">Up</span>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $latestLog ? $latestLog->response_time_ms . 'ms' : 'N/A' }}</p>
                                </div>
                            @else
                                <div class="text-right">
                                    <div class="flex items-center justify-end text-red-500">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        <span class="ml-2 font-bold text-lg">Down</span>
                                    </div>
                                    @if($latestLog && $latestLog->error_message)
                                        <p class="text-sm text-gray-500 truncate" title="{{ $latestLog->error_message }}">{{ $latestLog->error_message }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer -->
        <footer class="mt-12 text-center text-gray-400 text-sm">
            <p>Last updated: {{ now()->toDateTimeString() }}</p>
        </footer>

    </div>
</body>
</html>