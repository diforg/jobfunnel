<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'company' => $this->company,
            'source_name' => $this->source_name,
            'source_url' => $this->source_url,
            'apply_url' => $this->apply_url,
            'description' => $this->description,
            'salary_expectation' => $this->salary_expectation,
            'status' => $this->status,
            'notes' => $this->notes,
            'applied_at' => $this->applied_at?->toDateString(),
            'contacts' => JobContactResource::collection($this->whenLoaded('contacts')),
            'skills' => JobSkillResource::collection($this->whenLoaded('skills')),
            'timelines' => JobTimelineResource::collection($this->whenLoaded('timelines')),
            'resumes' => ResumeResource::collection($this->whenLoaded('resumes')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
