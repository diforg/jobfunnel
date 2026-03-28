<?php

use App\Models\Job;
use App\Models\JobContact;
use App\Models\JobSkill;
use App\Models\JobTimeline;
use App\Models\Skill;
use App\Models\User;

describe('Job model', function () {
    it('uses UUIDs as primary key', function () {
        $job = Job::factory()->create();

        expect($job->id)
            ->toBeString()
            ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/');
    });

    it('has many contacts', function () {
        $job = Job::factory()->create();
        JobContact::factory()->for($job)->count(3)->create();

        expect($job->contacts)->toHaveCount(3);
    });

    it('has many skills', function () {
        $job = Job::factory()->create();
        JobSkill::factory()->for($job)->count(2)->create();

        expect($job->skills)->toHaveCount(2);
    });

    it('has many timelines', function () {
        $job = Job::factory()->create();
        JobTimeline::factory()->for($job)->count(4)->create();

        expect($job->timelines)->toHaveCount(4);
    });

    it('orders timelines by happened_at ascending', function () {
        $job = Job::factory()->create();
        JobTimeline::factory()->for($job)->create(['happened_at' => '2024-06-15', 'stage' => 'hired']);
        JobTimeline::factory()->for($job)->create(['happened_at' => '2024-01-10', 'stage' => 'applied']);
        JobTimeline::factory()->for($job)->create(['happened_at' => '2024-03-20', 'stage' => 'offer']);

        $stages = $job->timelines()->orderBy('happened_at')->pluck('stage')->toArray();

        expect($stages)->toEqual(['applied', 'offer', 'hired']);
    });

    it('cascades deletion to contacts, skills, and timelines', function () {
        $job     = Job::factory()->create();
        $contact  = JobContact::factory()->for($job)->create();
        $skill    = JobSkill::factory()->for($job)->create();
        $timeline = JobTimeline::factory()->for($job)->create();

        $job->delete();

        expect(JobContact::find($contact->id))->toBeNull()
            ->and(JobSkill::find($skill->id))->toBeNull()
            ->and(JobTimeline::find($timeline->id))->toBeNull();
    });

    it('has the identified state factory', function () {
        $job = Job::factory()->identified()->create();
        expect($job->status)->toBe('identified');
    });

    it('has the applied state factory', function () {
        $job = Job::factory()->applied()->create();
        expect($job->status)->toBe('applied');
    });

    it('has the hired state factory', function () {
        $job = Job::factory()->hired()->create();
        expect($job->status)->toBe('hired');
    });

    it('can be filtered by status', function () {
        Job::factory()->applied()->count(3)->create();
        Job::factory()->identified()->count(2)->create();

        $applied = Job::where('status', 'applied')->count();
        expect($applied)->toBe(3);
    });
});

describe('Skill model', function () {
    it('uses UUIDs as primary key', function () {
        $skill = Skill::factory()->create();

        expect($skill->id)
            ->toBeString()
            ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/');
    });

    it('stores proficiency correctly', function () {
        $skill = Skill::factory()->create(['proficiency' => 'expert']);
        expect($skill->fresh()->proficiency)->toBe('expert');
    });

    it('orders skills by name alphabetically', function () {
        Skill::factory()->create(['name' => 'Zend']);
        Skill::factory()->create(['name' => 'Alpine']);
        Skill::factory()->create(['name' => 'Laravel']);

        $names = Skill::orderBy('name')->pluck('name')->toArray();
        expect($names)->toEqual(['Alpine', 'Laravel', 'Zend']);
    });

    it('requires a unique name per user', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        Skill::factory()->create(['name' => 'Laravel']); // auto user_id = $user->id

        expect(fn () => Skill::factory()->create(['name' => 'Laravel']))
            ->toThrow(\Illuminate\Database\QueryException::class);
    });
});
