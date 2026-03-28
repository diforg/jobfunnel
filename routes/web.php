<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthCheckController;

Route::get('/health-check', HealthCheckController::class)->name('health-check');

Route::get('/', function () {
    return response()->json(['message' => 'JobFunnel API is running']);
});
