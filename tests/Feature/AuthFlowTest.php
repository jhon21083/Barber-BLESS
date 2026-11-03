<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the landing page', function () {
    $this->get('/')->assertOk();
});

it('renders the login page', function () {
    $this->get(route('login'))->assertOk();
});

it('logs in a valid admin user and redirects to the dashboard', function () {
    User::factory()->create([
        'name' => 'Administrador',
        'email' => 'admin@barberbless.com',
    ]);

    $this->post(route('login'), [
        'email' => 'admin@barberbless.com',
        'password' => 'password',
    ])->assertRedirect('/dashboard');
});

it('blocks guests from the dashboard', function () {
    $this->get(route('dashboard'))->assertRedirect('/login');
});

it('lets authenticated users see the dashboard', function () {
    $user = User::factory()->create([
        'name' => 'Administrador',
        'email' => 'admin@barberbless.com',
    ]);

    $this->actingAs($user)->get(route('dashboard'))->assertOk();
});

it('logs the user out', function () {
    $user = User::factory()->create([
        'name' => 'Administrador',
        'email' => 'admin@barberbless.com',
    ]);

    $this->actingAs($user)->post(route('logout'))->assertRedirect();
    $this->assertGuest();
});
