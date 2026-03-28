<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobTimelineRequest;
use App\Http\Requests\UpdateJobTimelineRequest;
use App\Http\Resources\JobTimelineResource;
use App\Models\Job;
use App\Models\JobTimeline;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobTimelineController extends Controller
{
    public function index(Job $job): AnonymousResourceCollection
    {
        return JobTimelineResource::collection($job->timelines()->orderBy('happened_at')->get());
    }

    public function store(StoreJobTimelineRequest $request, Job $job): JobTimelineResource
    {
        $timeline = $job->timelines()->create($request->validated());

        return new JobTimelineResource($timeline);
    }

    public function show(Job $job, JobTimeline $timeline): JobTimelineResource
    {
        return new JobTimelineResource($timeline);
    }

    public function update(UpdateJobTimelineRequest $request, Job $job, JobTimeline $timeline): JobTimelineResource
    {
        $timeline->update($request->validated());

        return new JobTimelineResource($timeline);
    }

    public function destroy(Job $job, JobTimeline $timeline): JsonResponse
    {
        $timeline->delete();

        return response()->json(null, 204);
    }
}
