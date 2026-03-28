<?php

use App\Models\User;

describe('User registration', function () {
    it('renders the register page', function () {
        $this->get('/register')->assertOk()->assertInertia(fn ($p) => $p->component('Auth/Register'));
    });

    it('redirects authenticated users away from /register', function () {
        $this->actingAs(User::factory()->create())->get('/register')->assertRedirect('/');
    });

    it('registers a new user, logs them in and redirects to dashboard', function () {
        $this->post('/register', [
            'name'                  => 'João Silva',
            'email'                 => 'joao@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('users', ['email' => 'joao@example.com']);
        $this->assertAuthenticated();
    });

    it('fails with duplicate email', function () {
        User::factory()->create(['email' => 'exists@example.com']);

        $this->post('/register', [
            'name'                  => 'Other',
            'email'                 => 'exists@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors(['email']);
    });

    it('fails when password_confirmation does not match', function () {
        $this->post('/register', [
            'name'                  => 'Test',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'different',
        ])->assertSessionHasErrors(['password']);
    });

    it('fails when password is too short', function () {
        $this->post('/register', [
            'name'                  => 'Test',
            'email'                 => 'test@example.com',
            'password'              => 'short',
            'password_confirmation' => 'short',
        ])->assertSessionHasErrors(['password']);
    });
});
