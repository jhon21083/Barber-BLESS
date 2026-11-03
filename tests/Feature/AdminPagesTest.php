<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests from all admin pages to login', function (string $routeName) {
    $this->get(route($routeName))->assertRedirect(route('login'));
})->with([
    'admin.reservas',
    'admin.clientes',
    'admin.barberos',
    'admin.servicios',
    'admin.reportes',
    'admin.configuracion',
]);

it('allows authenticated users to view all admin sidebar pages', function (string $routeName) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route($routeName))
        ->assertOk();
})->with([
    'admin.reservas',
    'admin.clientes',
    'admin.barberos',
    'admin.servicios',
    'admin.reportes',
    'admin.configuracion',
]);

it('allows saving business configuration', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.configuracion'))
        ->post(route('admin.configuracion.update'), [
            'nombre_negocio' => 'Barber Bless Premium',
            'telefono' => '+591 7199 8888',
            'direccion' => 'Av. 16 de Julio #1440, La Paz, Bolivia',
            'codigo_postal' => '0101',
            'ciudad' => 'La Paz, Bolivia',
            'moneda' => 'BOB',
        ])
        ->assertRedirect(route('admin.configuracion'))
        ->assertSessionHas('success_config');
});
