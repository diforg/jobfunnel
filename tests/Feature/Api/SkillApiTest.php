<?php

use App\Models\Skill;

describe('Skills API', function () {

    describe('GET /api/v1/skills', function () {
        it('returns an empty list when there are no skills', function () {
            $this->getJson('/api/v1/skills')
                ->assertOk()
                ->assertJson(['data' => []]);
        });

        it('returns all skills ordered by name', function () {
            Skill::factory()->create(['name' => 'Zebra', 'category' => 'Backend', 'proficiency' => 'expert']);
            Skill::factory()->create(['name' => 'Alpha', 'category' => 'Frontend', 'proficiency' => 'beginner']);

            $response = $this->getJson('/api/v1/skills')->assertOk();

            expect($response->json('data.0.name'))->toBe('Alpha')
                ->and($response->json('data.1.name'))->toBe('Zebra');
        });

        it('returns skills with the correct resource structure', function () {
            $skill = Skill::factory()->create([
                'name'        => 'Laravel',
                'category'    => 'Backend',
                'proficiency' => 'expert',
            ]);

            $this->getJson('/api/v1/skills')
                ->assertOk()
                ->assertJsonStructure(['data' => [['id', 'name', 'category', 'proficiency', 'created_at', 'updated_at']]])
                ->assertJsonFragment([
                    'id'          => $skill->id,
                    'name'        => 'Laravel',
                    'category'    => 'Backend',
                    'proficiency' => 'expert',
                ]);
        });
    });

    describe('POST /api/v1/skills', function () {
        it('creates a skill with valid data', function () {
            $data = ['name' => 'Vue.js', 'category' => 'Frontend', 'proficiency' => 'intermediate'];

            $this->postJson('/api/v1/skills', $data)
                ->assertCreated()
                ->assertJsonFragment(['name' => 'Vue.js', 'category' => 'Frontend']);

            $this->assertDatabaseHas('skills', ['name' => 'Vue.js']);
        });

        it('fails validation when name is missing', function () {
            $this->postJson('/api/v1/skills', ['category' => 'Backend', 'proficiency' => 'expert'])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['name']);
        });

        it('fails validation with an invalid proficiency value', function () {
            $this->postJson('/api/v1/skills', ['name' => 'PHP', 'proficiency' => 'god_tier'])
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['proficiency']);
        });
    });

    describe('GET /api/v1/skills/{skill}', function () {
        it('returns a single skill', function () {
            $skill = Skill::factory()->create(['name' => 'Docker']);

            $this->getJson("/api/v1/skills/{$skill->id}")
                ->assertOk()
                ->assertJsonFragment(['name' => 'Docker']);
        });

        it('returns 404 for a non-existent skill', function () {
            $this->getJson('/api/v1/skills/00000000-0000-0000-0000-000000000000')
                ->assertNotFound();
        });
    });

    describe('PUT /api/v1/skills/{skill}', function () {
        it('updates a skill', function () {
            $skill = Skill::factory()->create(['name' => 'Old Name', 'proficiency' => 'beginner']);

            $this->putJson("/api/v1/skills/{$skill->id}", [
                'name'        => 'New Name',
                'category'    => $skill->category,
                'proficiency' => 'expert',
            ])->assertOk()->assertJsonFragment(['name' => 'New Name', 'proficiency' => 'expert']);

            $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => 'New Name', 'proficiency' => 'expert']);
        });
    });

    describe('DELETE /api/v1/skills/{skill}', function () {
        it('deletes a skill', function () {
            $skill = Skill::factory()->create();

            $this->deleteJson("/api/v1/skills/{$skill->id}")->assertNoContent();

            $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
        });
    });
});
