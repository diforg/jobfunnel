<?php

use Illuminate\Support\Facades\Route;

Route::get('/api/v1', function () {
    return response()->json([
        'message' => 'JobFunnel API v1',
        'status' => 'operational',
    ]);
});
