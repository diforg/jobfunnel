<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobTimeline extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'job_id',
        'stage',
        'happened_at',
        'notes',
    ];

    protected $casts = [
        'job_id' => 'string',
        'happened_at' => 'date',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
