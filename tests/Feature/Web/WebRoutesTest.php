<?php

use App\Models\Job;
use App\Models\Skill;

describe('Dashboard', function () {
    it('renders the dashboard page via Inertia', function () {
        $this->get('/')->assertOk()->assertInertia(fn ($page) => $page
            ->component('Dashboard/Index')
            ->has('totalJobs')
            ->has('statusCounts')
            ->has('recentJobs')
        );
    });

    it('shows the correct total job count', function () {
        Job::factory()->count(5)->create();

        $this->get('/')->assertInertia(fn ($page) => $page
            ->where('totalJobs', 5)
        );
    });

    it('limits recent jobs to 5', function () {
        Job::factory()->count(8)->create();

        $this->get('/')->assertInertia(fn ($page) => $page
            ->has('recentJobs', 5)
        );
    });

    it('returns statusCounts with all 9 statuses', function () {
        $this->get('/')->assertInertia(function ($page) {
            $counts = $page->toArray()['props']['statusCounts'];
            expect(array_keys($counts))->toEqual([
                'identified', 'applied', 'recruiter_interview', 'technical_interview',
                'technical_test', 'offer', 'hired', 'rejected', 'ghosted',
            ]);
        });
    });
});

describe('Jobs web routes', function () {
    it('renders the jobs index page', function () {
        $this->get('/jobs')->assertOk()->assertInertia(fn ($page) => $page
            ->component('Jobs/Index')
            ->has('jobs')
            ->has('currentStatus')
        );
    });

    it('renders the create job page', function () {
        $this->get('/jobs/create')->assertOk()->assertInertia(fn ($page) => $page
            ->component('Jobs/Create')
        );
    });

    it('creates a job and redirects to show page', function () {
        $this->post('/jobs', [
            'title'   => 'Dev Laravel',
            'company' => 'Acme Corp',
            'status'  => 'identified',
        ])->assertRedirect();

        $this->assertDatabaseHas('jobs', ['title' => 'Dev Laravel', 'company' => 'Acme Corp']);
    });

    it('fails to create a job with missing required fields', function () {
        $this->post('/jobs', ['status' => 'identified'])
            ->assertSessionHasErrors(['title', 'company']);
    });

    it('renders the job show page with relations', function () {
        $job = Job::factory()->create();
        $job->contacts()->create(['name' => 'Ana', 'role' => 'Lead']);

        $this->get("/jobs/{$job->id}")->assertOk()->assertInertia(fn ($page) => $page
            ->component('Jobs/Show')
            ->has('job.contacts', 1)
        );
    });

    it('renders the job edit page', function () {
        $job = Job::factory()->create();

        $this->get("/jobs/{$job->id}/edit")->assertOk()->assertInertia(fn ($page) => $page
            ->component('Jobs/Edit')
            ->has('job')
        );
    });

    it('updates a job and redirects', function () {
        $job = Job::factory()->identified()->create();

        $this->put("/jobs/{$job->id}", [
            'title'   => 'Updated Title',
            'company' => $job->company,
            'status'  => 'applied',
        ])->assertRedirect("/jobs/{$job->id}");

        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'title' => 'Updated Title', 'status' => 'applied']);
    });

    it('deletes a job and redirects to the index', function () {
        $job = Job::factory()->create();

        $this->delete("/jobs/{$job->id}")->assertRedirect('/jobs');
        $this->assertDatabaseMissing('jobs', ['id' => $job->id]);
    });

    it('filters jobs by status on index', function () {
        Job::factory()->applied()->create(['title' => 'Applied Job']);
        Job::factory()->identified()->create(['title' => 'Identified Job']);

        $this->get('/jobs?status=applied')->assertInertia(fn ($page) => $page
            ->has('jobs', 1)
            ->where('currentStatus', 'applied')
        );
    });
});

describe('Skills web routes', function () {
    it('renders the skills index with all skills', function () {
        Skill::factory()->count(3)->create();

        $this->get('/skills')->assertOk()->assertInertia(fn ($page) => $page
            ->component('Skills/Index')
            ->has('skills', 3)
        );
    });

    it('renders the create skill page', function () {
        $this->get('/skills/create')->assertOk()->assertInertia(fn ($page) => $page
            ->component('Skills/Create')
        );
    });

    it('creates a skill and redirects to index', function () {
        $this->post('/skills', [
            'name'        => 'Rust',
            'category'    => 'Backend',
            'proficiency' => 'beginner',
        ])->assertRedirect('/skills');

        $this->assertDatabaseHas('skills', ['name' => 'Rust']);
    });

    it('renders the skill edit page', function () {
        $skill = Skill::factory()->create();

        $this->get("/skills/{$skill->id}/edit")->assertOk()->assertInertia(fn ($page) => $page
            ->component('Skills/Edit')
            ->has('skill')
        );
    });

    it('updates a skill and redirects to index', function () {
        $skill = Skill::factory()->create(['name' => 'Old', 'proficiency' => 'beginner']);

        $this->put("/skills/{$skill->id}", [
            'name'        => 'Updated',
            'category'    => $skill->category,
            'proficiency' => 'expert',
        ])->assertRedirect('/skills');

        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => 'Updated', 'proficiency' => 'expert']);
    });

    it('deletes a skill and redirects to index', function () {
        $skill = Skill::factory()->create();

        $this->delete("/skills/{$skill->id}")->assertRedirect('/skills');
        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    });
});
