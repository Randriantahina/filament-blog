<?php

namespace App\Http\Controllers;

use App\Services\StatusPageService;

class StatusPageController extends Controller
{
    public function __construct(protected StatusPageService $statusPageService)
    {
    }

    public function show(string $slug)
    {
        $statusPage = $this->statusPageService->getPageForPublicView($slug);

        return view('status-page', compact('statusPage'));
    }
}