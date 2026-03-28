<?php

use App\Http\Controllers\Api\V1\JobContactController;
use App\Http\Controllers\Api\V1\JobController;
use App\Http\Controllers\Api\V1\JobSkillController;
use App\Http\Controllers\Api\V1\JobTimelineController;
use App\Http\Controllers\Api\V1\ResumeController;
use App\Http\Controllers\Api\V1\SkillController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'JobFunnel API v1',
            'status' => 'operational',
        ]);
    });

    Route::apiResource('skills', SkillController::class);

    Route::apiResource('jobs', JobController::class);

    Route::apiResource('jobs.contacts', JobContactController::class)
        ->parameters(['contacts' => 'contact']);

    Route::apiResource('jobs.skills', JobSkillController::class)
        ->parameters(['skills' => 'skill']);

    Route::apiResource('jobs.timelines', JobTimelineController::class)
        ->parameters(['timelines' => 'timeline']);

    Route::apiResource('jobs.resumes', ResumeController::class)
        ->parameters(['resumes' => 'resume']);
});
