<?php

namespace App\Http\Controllers;

use App\Models\StatusPage;
use Illuminate\Http\Request;

class StatusPageController extends Controller
{
    public function show(StatusPage $statusPage)
    {
        $statusPage->load('monitors.checkLogs');

        return view('status-page', compact('statusPage'));
    }
}
