<?php

use App\Http\Controllers\Api\HeartbeatController;
use App\Http\Controllers\Api\MonitorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/heartbeat/{monitor:uuid}', HeartbeatController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('monitors', MonitorController::class);
});
