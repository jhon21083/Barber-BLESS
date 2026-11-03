<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('landing page contains all navigation sections', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('id="destacados"', false)
        ->assertSee('id="servicios"', false)
        ->assertSee('id="folio"', false)
        ->assertSee('id="barberos"', false)
        ->assertSee('id="como-funciona"', false)
        ->assertSee('id="ayuda"', false);
});
