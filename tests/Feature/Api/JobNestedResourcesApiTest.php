<?php

use App\Models\Job;
use App\Models\JobContact;
use App\Models\JobSkill;
use App\Models\JobTimeline;

describe('Job Contacts API', function () {
    it('lists contacts for a job', function () {
        $job = Job::factory()->create();
        $job->contacts()->create(['name' => 'João', 'role' => 'CTO']);
        $job->contacts()->create(['name' => 'Ana', 'role' => 'Recruiter']);

        $this->getJson("/api/v1/jobs/{$job->id}/contacts")
            ->assertOk()
            ->assertJsonCount(2, 'data');
    });

    it('creates a contact for a job', function () {
        $job = Job::factory()->create();

        $this->postJson("/api/v1/jobs/{$job->id}/contacts", [
            'name'  => 'Maria Silva',
            'role'  => 'Tech Recruiter',
            'email' => 'maria@example.com',
        ])->assertCreated()
            ->assertJsonFragment(['name' => 'Maria Silva']);

        $this->assertDatabaseHas('job_contacts', ['job_id' => $job->id, 'name' => 'Maria Silva']);
    });

    it('fails to create a contact without a name', function () {
        $job = Job::factory()->create();

        $this->postJson("/api/v1/jobs/{$job->id}/contacts", ['role' => 'Recruiter'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });

    it('shows a single contact', function () {
        $job     = Job::factory()->create();
        $contact = $job->contacts()->create(['name' => 'Carlos', 'role' => null]);

        $this->getJson("/api/v1/jobs/{$job->id}/contacts/{$contact->id}")
            ->assertOk()
            ->assertJsonPath('data.name', 'Carlos');
    });

    it('updates a contact', function () {
        $job     = Job::factory()->create();
        $contact = $job->contacts()->create(['name' => 'Old Name', 'role' => null]);

        $this->putJson("/api/v1/jobs/{$job->id}/contacts/{$contact->id}", [
            'name' => 'New Name',
        ])->assertOk()->assertJsonPath('data.name', 'New Name');
    });

    it('deletes a contact', function () {
        $job     = Job::factory()->create();
        $contact = $job->contacts()->create(['name' => 'To Delete', 'role' => null]);

        $this->deleteJson("/api/v1/jobs/{$job->id}/contacts/{$contact->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('job_contacts', ['id' => $contact->id]);
    });
});

describe('Job Skills API', function () {
    it('lists skills for a job', function () {
        $job = Job::factory()->create();
        $job->skills()->create(['skill_name' => 'PHP', 'level' => 'required', 'matched' => true]);
        $job->skills()->create(['skill_name' => 'Docker', 'level' => 'nice_to_have', 'matched' => false]);

        $this->getJson("/api/v1/jobs/{$job->id}/skills")
            ->assertOk()
            ->assertJsonCount(2, 'data');
    });

    it('creates a skill requirement for a job', function () {
        $job = Job::factory()->create();

        $this->postJson("/api/v1/jobs/{$job->id}/skills", [
            'skill_name' => 'Vue.js',
            'level'      => 'required',
            'matched'    => true,
        ])->assertCreated()
            ->assertJsonFragment(['skill_name' => 'Vue.js', 'matched' => true]);
    });

    it('fails with an invalid level value', function () {
        $job = Job::factory()->create();

        $this->postJson("/api/v1/jobs/{$job->id}/skills", [
            'skill_name' => 'PHP',
            'level'      => 'mandatory',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['level']);
    });

    it('updates the matched status of a skill', function () {
        $job   = Job::factory()->create();
        $skill = $job->skills()->create(['skill_name' => 'Laravel', 'level' => 'required', 'matched' => false]);

        $this->putJson("/api/v1/jobs/{$job->id}/skills/{$skill->id}", [
            'skill_name' => 'Laravel',
            'level'      => 'required',
            'matched'    => true,
        ])->assertOk()->assertJsonPath('data.matched', true);

        $this->assertDatabaseHas('job_skills', ['id' => $skill->id, 'matched' => true]);
    });

    it('deletes a skill requirement', function () {
        $job   = Job::factory()->create();
        $skill = $job->skills()->create(['skill_name' => 'PHP', 'level' => 'required', 'matched' => false]);

        $this->deleteJson("/api/v1/jobs/{$job->id}/skills/{$skill->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('job_skills', ['id' => $skill->id]);
    });
});

describe('Job Timelines API', function () {
    it('lists timeline events ordered by happened_at ascending', function () {
        $job = Job::factory()->create();
        $job->timelines()->create(['stage' => 'applied', 'happened_at' => '2025-02-01']);
        $job->timelines()->create(['stage' => 'identified', 'happened_at' => '2025-01-01']);

        $response = $this->getJson("/api/v1/jobs/{$job->id}/timelines")->assertOk();

        expect($response->json('data.0.stage'))->toBe('identified')
            ->and($response->json('data.1.stage'))->toBe('applied');
    });

    it('creates a timeline event', function () {
        $job = Job::factory()->create();

        $this->postJson("/api/v1/jobs/{$job->id}/timelines", [
            'stage'       => 'recruiter_interview',
            'happened_at' => '2025-03-15',
            'notes'       => 'Entrevista de 30 min',
        ])->assertCreated()
            ->assertJsonFragment(['stage' => 'recruiter_interview', 'happened_at' => '2025-03-15']);
    });

    it('fails to create a timeline event without required fields', function () {
        $job = Job::factory()->create();

        $this->postJson("/api/v1/jobs/{$job->id}/timelines", ['notes' => 'sem stage e data'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['stage', 'happened_at']);
    });

    it('deletes a timeline event', function () {
        $job      = Job::factory()->create();
        $timeline = $job->timelines()->create(['stage' => 'applied', 'happened_at' => '2025-01-01']);

        $this->deleteJson("/api/v1/jobs/{$job->id}/timelines/{$timeline->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('job_timelines', ['id' => $timeline->id]);
    });
});
