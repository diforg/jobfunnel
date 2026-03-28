<?php

use App\Models\Job;

describe('Jobs API', function () {

    describe('GET /api/v1/jobs', function () {
        it('returns an empty list when there are no jobs', function () {
            $this->getJson('/api/v1/jobs')
                ->assertOk()
                ->assertJson(['data' => []]);
        });

        it('returns all jobs ordered by most recent first', function () {
            $old = Job::factory()->create(['created_at' => now()->subDays(5)]);
            $new = Job::factory()->create(['created_at' => now()]);

            $response = $this->getJson('/api/v1/jobs')->assertOk();

            expect($response->json('data.0.id'))->toBe($new->id)
                ->and($response->json('data.1.id'))->toBe($old->id);
        });

        it('filters jobs by status', function () {
            Job::factory()->applied()->create(['title' => 'Applied Job']);
            Job::factory()->identified()->create(['title' => 'Identified Job']);

            $response = $this->getJson('/api/v1/jobs?status=applied')->assertOk();

            expect($response->json('data'))->toHaveCount(1)
                ->and($response->json('data.0.status'))->toBe('applied');
        });

        it('filters jobs by company with case-insensitive partial match', function () {
            Job::factory()->create(['company' => 'Nubank']);
            Job::factory()->create(['company' => 'PicPay']);

            $response = $this->getJson('/api/v1/jobs?company=nub')->assertOk();

            expect($response->json('data'))->toHaveCount(1)
                ->and($response->json('data.0.company'))->toBe('Nubank');
        });

        it('returns the correct resource structure', function () {
            Job::factory()->create();

            $this->getJson('/api/v1/jobs')
                ->assertOk()
                ->assertJsonStructure([
                    'data' => [[
                        'id', 'title', 'company', 'status',
                        'source_name', 'source_url', 'apply_url',
                        'description', 'salary_expectation', 'notes',
                        'applied_at', 'created_at', 'updated_at',
                    ]]
                ]);
        });
    });

    describe('POST /api/v1/jobs', function () {
        it('creates a job with minimum required fields', function () {
            $data = ['title' => 'Dev Sênior', 'company' => 'Acme', 'status' => 'identified'];

            $this->postJson('/api/v1/jobs', $data)
                ->assertCreated()
                ->assertJsonFragment(['title' => 'Dev Sênior', 'company' => 'Acme', 'status' => 'identified']);

            $this->assertDatabaseHas('jobs', ['title' => 'Dev Sênior', 'company' => 'Acme']);
        });

        it('creates a job with all fields', function () {
            $data = [
                'title'              => 'Full Stack',
                'company'            => 'Nubank',
                'source_name'        => 'LinkedIn',
                'source_url'         => 'https://linkedin.com/jobs/1',
                'apply_url'          => 'https://nubank.com/vagas/1',
                'description'        => 'Descrição da vaga',
                'salary_expectation' => 15000.00,
                'status'             => 'applied',
                'notes'              => 'Muito boa empresa',
                'applied_at'         => '2025-01-10',
            ];

            $this->postJson('/api/v1/jobs', $data)
                ->assertCreated()
                ->assertJsonPath('data.salary_expectation', '15000.00')
                ->assertJsonPath('data.applied_at', '2025-01-10');
        });

        it('fails validation when title is missing', function () {
            $this->postJson('/api/v1/jobs', ['company' => 'Acme', 'status' => 'identified'])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['title']);
        });

        it('fails validation when company is missing', function () {
            $this->postJson('/api/v1/jobs', ['title' => 'Dev', 'status' => 'identified'])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['company']);
        });

        it('fails validation with an invalid status', function () {
            $this->postJson('/api/v1/jobs', ['title' => 'Dev', 'company' => 'Acme', 'status' => 'invalid'])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['status']);
        });

        it('returns nested empty relations on create', function () {
            $this->postJson('/api/v1/jobs', ['title' => 'Dev', 'company' => 'Acme', 'status' => 'identified'])
                ->assertCreated()
                ->assertJsonPath('data.contacts', [])
                ->assertJsonPath('data.skills', [])
                ->assertJsonPath('data.timelines', [])
                ->assertJsonPath('data.resumes', []);
        });
    });

    describe('GET /api/v1/jobs/{job}', function () {
        it('returns a job with nested relations', function () {
            $job = Job::factory()->create();
            $job->contacts()->create(['name' => 'Ana', 'role' => 'Recruiter']);
            $job->skills()->create(['skill_name' => 'PHP', 'level' => 'required', 'matched' => true]);
            $job->timelines()->create(['stage' => 'applied', 'happened_at' => '2025-01-10']);

            $response = $this->getJson("/api/v1/jobs/{$job->id}")->assertOk();

            expect($response->json('data.contacts'))->toHaveCount(1)
                ->and($response->json('data.skills'))->toHaveCount(1)
                ->and($response->json('data.timelines'))->toHaveCount(1)
                ->and($response->json('data.contacts.0.name'))->toBe('Ana')
                ->and($response->json('data.skills.0.skill_name'))->toBe('PHP')
                ->and($response->json('data.skills.0.matched'))->toBeTrue();
        });

        it('returns 404 for a non-existent job', function () {
            $this->getJson('/api/v1/jobs/00000000-0000-0000-0000-000000000000')
                ->assertNotFound();
        });
    });

    describe('PUT /api/v1/jobs/{job}', function () {
        it('updates a job status', function () {
            $job = Job::factory()->identified()->create();

            $this->putJson("/api/v1/jobs/{$job->id}", [
                'title'   => $job->title,
                'company' => $job->company,
                'status'  => 'applied',
            ])->assertOk()->assertJsonPath('data.status', 'applied');

            $this->assertDatabaseHas('jobs', ['id' => $job->id, 'status' => 'applied']);
        });
    });

    describe('DELETE /api/v1/jobs/{job}', function () {
        it('deletes a job', function () {
            $job = Job::factory()->create();

            $this->deleteJson("/api/v1/jobs/{$job->id}")->assertNoContent();

            $this->assertDatabaseMissing('jobs', ['id' => $job->id]);
        });

        it('cascades deletion to contacts, skills and timelines', function () {
            $job = Job::factory()->create();
            $job->contacts()->create(['name' => 'Contact', 'role' => null]);
            $job->skills()->create(['skill_name' => 'PHP', 'level' => 'required', 'matched' => false]);
            $job->timelines()->create(['stage' => 'applied', 'happened_at' => '2025-01-01']);

            $this->deleteJson("/api/v1/jobs/{$job->id}")->assertNoContent();

            $this->assertDatabaseMissing('job_contacts', ['job_id' => $job->id]);
            $this->assertDatabaseMissing('job_skills', ['job_id' => $job->id]);
            $this->assertDatabaseMissing('job_timelines', ['job_id' => $job->id]);
        });
    });
});
