<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'company',
        'source_name',
        'source_url',
        'apply_url',
        'description',
        'salary_expectation',
        'status',
        'notes',
        'applied_at',
    ];

    protected $casts = [
        'salary_expectation' => 'decimal:2',
        'applied_at' => 'date',
        'status' => 'string',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(JobContact::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(JobSkill::class);
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(JobTimeline::class)->orderBy('happened_at', 'asc');
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class);
    }
}
