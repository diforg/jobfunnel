<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'category',
        'proficiency',
    ];

    protected $casts = [
        'proficiency' => 'string',
    ];
}
