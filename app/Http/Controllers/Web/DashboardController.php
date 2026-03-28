<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $allStatuses = [
            'identified', 'applied', 'recruiter_interview', 'technical_interview',
            'technical_test', 'offer', 'hired', 'rejected', 'ghosted',
        ];

        $byStatus = Job::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        $statusCounts = [];
        foreach ($allStatuses as $status) {
            $statusCounts[$status] = (int) ($byStatus[$status] ?? 0);
        }

        return Inertia::render('Dashboard/Index', [
            'totalJobs' => Job::count(),
            'statusCounts' => $statusCounts,
            'recentJobs' => Job::orderByDesc('created_at')->limit(5)->get(),
        ]);
    }
}
