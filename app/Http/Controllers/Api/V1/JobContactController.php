<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobContactRequest;
use App\Http\Requests\UpdateJobContactRequest;
use App\Http\Resources\JobContactResource;
use App\Models\Job;
use App\Models\JobContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobContactController extends Controller
{
    public function index(Job $job): AnonymousResourceCollection
    {
        return JobContactResource::collection($job->contacts()->orderBy('name')->get());
    }

    public function store(StoreJobContactRequest $request, Job $job): JobContactResource
    {
        $contact = $job->contacts()->create($request->validated());

        return new JobContactResource($contact);
    }

    public function show(Job $job, JobContact $contact): JobContactResource
    {
        return new JobContactResource($contact);
    }

    public function update(UpdateJobContactRequest $request, Job $job, JobContact $contact): JobContactResource
    {
        $contact->update($request->validated());

        return new JobContactResource($contact);
    }

    public function destroy(Job $job, JobContact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json(null, 204);
    }
}
