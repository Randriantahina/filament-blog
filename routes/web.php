<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusPageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/status/{statusPage:slug}', [StatusPageController::class, 'show'])->name('status-page');
