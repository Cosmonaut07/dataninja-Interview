<?php

use App\Models\User;
use Nette\Utils\Random;

it('can\'t create user from invalid data', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john.doe',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->postJson('/api/auth/register', $userData);
    $response->assertStatus(422);
    $userData = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password123',
    ];

    $response = $this->postJson('/api/auth/register', $userData);
    $response->assertStatus(422);

});


it('can create user', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->postJson('/api/auth/register', $userData);
    $response->assertStatus(201);

});

it('can\'t login with invalid credentials', function () {

    User::factory()->create([
        'email' => 'john.doe@example.com',
        'password' => 'password',
    ]);

    $userData = [
        'email' => 'john.notDoe@example.com',
        'password' => 'password',
    ];

    $response = $this->postJson('/api/auth/login', $userData);
    $response->assertStatus(401);


});

it('can login user', function () {

    User::factory()->create([
        'email' => 'john.doe@example.com',
        'password' => 'password',
    ]);

    $userData = [
        'email' => 'john.doe@example.com',
        'password' => 'password',
    ];

    $response = $this->postJson('/api/auth/login', $userData);
    $response->assertStatus(200);
});
