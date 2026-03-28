<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSkill extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'job_id',
        'skill_name',
        'level',
        'matched',
    ];

    protected $casts = [
        'job_id' => 'string',
        'matched' => 'boolean',
        'level' => 'string',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
