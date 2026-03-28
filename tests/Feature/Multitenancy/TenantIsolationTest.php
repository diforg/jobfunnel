<?php

use App\Models\Job;
use App\Models\Skill;
use App\Models\User;

describe('Multitenancy isolation', function () {
    it('user only sees their own jobs on the index', function () {
        $alice = User::factory()->create();
        $bob   = User::factory()->create();

        $this->actingAs($alice);
        Job::factory()->count(3)->create(); // alice's jobs

        // Create bob's job bypassing the creating-event scope
        $this->actingAs($bob);
        Job::factory()->count(2)->create(); // bob's jobs

        $this->actingAs($alice);
        $this->get('/jobs')->assertInertia(fn ($page) => $page->has('jobs', 3));
    });

    it('user cannot view another user\'s job', function () {
        $alice = User::factory()->create();
        $bob   = User::factory()->create();

        $this->actingAs($alice);
        $aliceJob = Job::factory()->create();

        $this->actingAs($bob)->get("/jobs/{$aliceJob->id}")->assertNotFound();
    });

    it('user cannot update another user\'s job', function () {
        $alice = User::factory()->create();
        $bob   = User::factory()->create();

        $this->actingAs($alice);
        $aliceJob = Job::factory()->create();

        $this->actingAs($bob)->put("/jobs/{$aliceJob->id}", [
            'title'   => 'Hacked',
            'company' => 'Evil Corp',
            'status'  => 'identified',
        ])->assertNotFound();

        $this->assertDatabaseMissing('jobs', ['title' => 'Hacked']);
    });

    it('user cannot delete another user\'s job', function () {
        $alice = User::factory()->create();
        $bob   = User::factory()->create();

        $this->actingAs($alice);
        $aliceJob = Job::factory()->create();

        $this->actingAs($bob)->delete("/jobs/{$aliceJob->id}")->assertNotFound();
        $this->assertDatabaseHas('jobs', ['id' => $aliceJob->id]);
    });

    it('user only sees their own skills', function () {
        $alice = User::factory()->create();
        $bob   = User::factory()->create();

        $this->actingAs($alice);
        Skill::factory()->count(2)->create();

        $this->actingAs($bob);
        Skill::factory()->count(4)->create();

        $this->actingAs($alice);
        $this->get('/skills')->assertInertia(fn ($page) => $page->has('skills', 2));
    });

    it('allows two users to have a skill with the same name', function () {
        $alice = User::factory()->create();
        $bob   = User::factory()->create();

        $this->actingAs($alice);
        Skill::factory()->create(['name' => 'Laravel']);

        $this->actingAs($bob);
        // Bob can also have "Laravel" — no unique-key violation
        expect(fn () => Skill::factory()->create(['name' => 'Laravel']))->not->toThrow(\Exception::class);
    });

    it('dashboard totalJobs only counts the current user\'s jobs', function () {
        $alice = User::factory()->create();
        $bob   = User::factory()->create();

        $this->actingAs($alice);
        Job::factory()->count(4)->create();

        $this->actingAs($bob);
        Job::factory()->count(1)->create();

        $this->actingAs($alice);
        $this->get('/')->assertInertia(fn ($p) => $p->where('totalJobs', 4));
    });
});
