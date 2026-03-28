<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobContact extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'job_id',
        'name',
        'role',
        'email',
        'phone',
        'linkedin_url',
        'notes',
    ];

    protected $casts = [
        'job_id' => 'string',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
