<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('allows a user to register and access the dashboard', function () {
    $response = $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();

    $this->get('/dashboard')
        ->assertOk()
        ->assertSee('Dashboard');
});

it('allows a user to log in and log out', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('password123'),
    ]);

    $this->post('/login', [
        'email' => 'john@example.com',
        'password' => 'password123',
    ])->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);

    $this->post('/logout')
        ->assertRedirect('/login');

    $this->assertGuest();
});
