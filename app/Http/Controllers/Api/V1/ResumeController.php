<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResumeRequest;
use App\Http\Requests\UpdateResumeRequest;
use App\Http\Resources\ResumeResource;
use App\Models\Job;
use App\Models\Resume;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ResumeController extends Controller
{
    public function index(Job $job): AnonymousResourceCollection
    {
        return ResumeResource::collection($job->resumes()->orderByDesc('created_at')->get());
    }

    public function store(StoreResumeRequest $request, Job $job): ResumeResource
    {
        $resume = $job->resumes()->create($request->validated());

        return new ResumeResource($resume);
    }

    public function show(Job $job, Resume $resume): ResumeResource
    {
        return new ResumeResource($resume);
    }

    public function update(UpdateResumeRequest $request, Job $job, Resume $resume): ResumeResource
    {
        $resume->update($request->validated());

        return new ResumeResource($resume);
    }

    public function destroy(Job $job, Resume $resume): JsonResponse
    {
        $resume->delete();

        return response()->json(null, 204);
    }
}
