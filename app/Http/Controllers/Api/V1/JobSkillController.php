<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobSkillRequest;
use App\Http\Requests\UpdateJobSkillRequest;
use App\Http\Resources\JobSkillResource;
use App\Models\Job;
use App\Models\JobSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobSkillController extends Controller
{
    public function index(Job $job): AnonymousResourceCollection
    {
        return JobSkillResource::collection($job->skills()->orderBy('skill_name')->get());
    }

    public function store(StoreJobSkillRequest $request, Job $job): JobSkillResource
    {
        $skill = $job->skills()->create($request->validated());

        return new JobSkillResource($skill);
    }

    public function show(Job $job, JobSkill $skill): JobSkillResource
    {
        return new JobSkillResource($skill);
    }

    public function update(UpdateJobSkillRequest $request, Job $job, JobSkill $skill): JobSkillResource
    {
        $skill->update($request->validated());

        return new JobSkillResource($skill);
    }

    public function destroy(Job $job, JobSkill $skill): JsonResponse
    {
        $skill->delete();

        return response()->json(null, 204);
    }
}
