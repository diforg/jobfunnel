<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobTimeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JobTimelineController extends Controller
{
    public function store(Request $request, Job $job): RedirectResponse
    {
        $request->validate([
            'stage' => 'required|string|max:255',
            'happened_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $job->timelines()->create($request->only(['stage', 'happened_at', 'notes']));

        return back()->with('success', 'Evento adicionado à timeline!');
    }

    public function destroy(Job $job, JobTimeline $timeline): RedirectResponse
    {
        $timeline->delete();

        return back()->with('success', 'Evento removido da timeline!');
    }
}
