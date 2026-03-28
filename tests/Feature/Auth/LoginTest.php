<?php

use App\Models\User;

describe('Login', function () {
    it('renders the login page', function () {
        $this->get('/login')->assertOk()->assertInertia(fn ($p) => $p->component('Auth/Login'));
    });

    it('redirects authenticated users away from /login', function () {
        $this->actingAs(User::factory()->create())->get('/login')->assertRedirect('/');
    });

    it('logs in with correct credentials', function () {
        $user = User::factory()->create(['email' => 'user@example.com', 'password' => bcrypt('secret123')]);

        $this->post('/login', ['email' => 'user@example.com', 'password' => 'secret123'])
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    });

    it('fails with wrong password', function () {
        User::factory()->create(['email' => 'user@example.com', 'password' => bcrypt('correct')]);

        $this->post('/login', ['email' => 'user@example.com', 'password' => 'wrong'])
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    });

    it('fails when the user does not exist', function () {
        $this->post('/login', ['email' => 'nobody@example.com', 'password' => 'anything'])
            ->assertSessionHasErrors(['email']);
    });

    it('validates required fields', function () {
        $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
    });
});

describe('Logout', function () {
    it('logs out the authenticated user', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/logout')->assertRedirect('/login');

        $this->assertGuest();
    });

    it('unauthenticated users cannot logout', function () {
        $this->post('/logout')->assertRedirect('/login');
    });
});
