<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class JobController extends Controller
{
    public function index(): Response
    {
        $query = Job::query();

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        return Inertia::render('Jobs/Index', [
            'jobs' => $query->orderByDesc('created_at')->get(),
            'currentStatus' => request('status', ''),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Jobs/Create');
    }

    public function store(StoreJobRequest $request): RedirectResponse
    {
        $job = Job::create($request->validated());

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Vaga criada com sucesso!');
    }

    public function show(Job $job): Response
    {
        return Inertia::render('Jobs/Show', [
            'job' => $job->load(['contacts', 'skills', 'timelines', 'resumes']),
        ]);
    }

    public function edit(Job $job): Response
    {
        return Inertia::render('Jobs/Edit', [
            'job' => $job,
        ]);
    }

    public function update(UpdateJobRequest $request, Job $job): RedirectResponse
    {
        $job->update($request->validated());

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Vaga atualizada com sucesso!');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();

        return redirect()->route('jobs.index')
            ->with('success', 'Vaga excluída com sucesso!');
    }
}
