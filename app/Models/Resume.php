<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'job_id',
        'version_name',
        'file_path',
        'sent_at',
        'notes',
    ];

    protected $casts = [
        'job_id' => 'string',
        'sent_at' => 'date',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
