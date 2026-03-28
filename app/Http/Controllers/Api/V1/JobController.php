<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Job::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('company')) {
            $query->where('company', 'ilike', '%' . $request->input('company') . '%');
        }

        $jobs = $query->withCount(['contacts', 'skills', 'timelines', 'resumes'])
            ->orderByDesc('created_at')
            ->get();

        return JobResource::collection($jobs);
    }

    public function store(StoreJobRequest $request): JobResource
    {
        $job = Job::create($request->validated());

        return new JobResource($job->load(['contacts', 'skills', 'timelines', 'resumes']));
    }

    public function show(Job $job): JobResource
    {
        return new JobResource($job->load(['contacts', 'skills', 'timelines', 'resumes']));
    }

    public function update(UpdateJobRequest $request, Job $job): JobResource
    {
        $job->update($request->validated());

        return new JobResource($job->load(['contacts', 'skills', 'timelines', 'resumes']));
    }

    public function destroy(Job $job): JsonResponse
    {
        $job->delete();

        return response()->json(null, 204);
    }
}
