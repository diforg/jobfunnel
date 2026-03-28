<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
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
        'applied_at'         => 'date',
        'status'             => 'string',
    ];

    /** Automatically scope queries and auto-fill user_id when authenticated. */
    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $query) {
            if (auth()->check()) {
                $query->where('user_id', auth()->id());
            }
        });

        static::creating(function (self $model) {
            if (auth()->check() && is_null($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
