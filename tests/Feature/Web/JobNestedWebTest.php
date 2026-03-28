<?php

use App\Models\Job;
use App\Models\JobContact;
use App\Models\JobSkill;
use App\Models\JobTimeline;

describe('Job contacts (web nested routes)', function () {
    it('stores a contact and redirects back', function () {
        $job = Job::factory()->create();

        $this->post("/jobs/{$job->id}/contacts", [
            'name' => 'Maria',
            'role' => 'Recruiter',
        ])->assertRedirect();

        $this->assertDatabaseHas('job_contacts', ['job_id' => $job->id, 'name' => 'Maria']);
    });

    it('deletes a contact and redirects back', function () {
        $job     = Job::factory()->create();
        $contact = JobContact::factory()->for($job)->create();

        $this->delete("/jobs/{$job->id}/contacts/{$contact->id}")->assertRedirect();
        $this->assertDatabaseMissing('job_contacts', ['id' => $contact->id]);
    });

    it('requires name when creating a contact', function () {
        $job = Job::factory()->create();

        $this->post("/jobs/{$job->id}/contacts", ['role' => 'HR'])
            ->assertSessionHasErrors(['name']);
    });
});

describe('Job skills (web nested routes)', function () {
    it('stores a job skill and redirects back', function () {
        $job = Job::factory()->create();

        $this->post("/jobs/{$job->id}/skills", [
            'skill_name' => 'Laravel',
            'level'      => 'required',
        ])->assertRedirect();

        $this->assertDatabaseHas('job_skills', ['job_id' => $job->id, 'skill_name' => 'Laravel']);
    });

    it('deletes a job skill and redirects back', function () {
        $job      = Job::factory()->create();
        $jobSkill = JobSkill::factory()->for($job)->create();

        $this->delete("/jobs/{$job->id}/skills/{$jobSkill->id}")->assertRedirect();
        $this->assertDatabaseMissing('job_skills', ['id' => $jobSkill->id]);
    });

    it('requires skill_name and level when creating a job skill', function () {
        $job = Job::factory()->create();

        $this->post("/jobs/{$job->id}/skills", [])
            ->assertSessionHasErrors(['skill_name', 'level']);
    });
});

describe('Job timelines (web nested routes)', function () {
    it('stores a timeline entry and redirects back', function () {
        $job = Job::factory()->create();

        $this->post("/jobs/{$job->id}/timelines", [
            'stage'       => 'applied',
            'happened_at' => '2024-06-01',
        ])->assertRedirect();

        $this->assertDatabaseHas('job_timelines', ['job_id' => $job->id, 'stage' => 'applied']);
    });

    it('deletes a timeline entry and redirects back', function () {
        $job      = Job::factory()->create();
        $timeline = JobTimeline::factory()->for($job)->create();

        $this->delete("/jobs/{$job->id}/timelines/{$timeline->id}")->assertRedirect();
        $this->assertDatabaseMissing('job_timelines', ['id' => $timeline->id]);
    });

    it('requires stage and happened_at when creating a timeline entry', function () {
        $job = Job::factory()->create();

        $this->post("/jobs/{$job->id}/timelines", [])
            ->assertSessionHasErrors(['stage', 'happened_at']);
    });
});
