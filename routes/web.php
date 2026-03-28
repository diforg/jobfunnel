<?php

use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\JobContactController;
use App\Http\Controllers\Web\JobController;
use App\Http\Controllers\Web\JobSkillController;
use App\Http\Controllers\Web\JobTimelineController;
use App\Http\Controllers\Web\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('/health-check', HealthCheckController::class)->name('health-check');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('jobs', JobController::class);
Route::resource('skills', SkillController::class)->except(['show']);

// Nested job sub-resources (used from Jobs/Show page)
Route::post('/jobs/{job}/contacts', [JobContactController::class, 'store'])->name('jobs.contacts.store');
Route::delete('/jobs/{job}/contacts/{contact}', [JobContactController::class, 'destroy'])->name('jobs.contacts.destroy');

Route::post('/jobs/{job}/skills', [JobSkillController::class, 'store'])->name('jobs.skills.store');
Route::patch('/jobs/{job}/skills/{skill}', [JobSkillController::class, 'update'])->name('jobs.skills.update');
Route::delete('/jobs/{job}/skills/{skill}', [JobSkillController::class, 'destroy'])->name('jobs.skills.destroy');

Route::post('/jobs/{job}/timelines', [JobTimelineController::class, 'store'])->name('jobs.timelines.store');
Route::delete('/jobs/{job}/timelines/{timeline}', [JobTimelineController::class, 'destroy'])->name('jobs.timelines.destroy');
