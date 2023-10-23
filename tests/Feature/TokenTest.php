<?php

use App\Models\User;
use App\Models\UserToken;


it('can\'t create token without authorization ', function () {
    $response = $this->postJson('api/token/create');
    $response->assertStatus(401);
});


it('can\'t create new tokens if not verified', function () {

    $user = User::factory()->create();
    $userToken = $user->createToken('test')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $userToken,
    ])->postJson('api/token/create');
    $response->assertStatus(403);

});

it('can create new tokens', function () {
    $user = User::factory()->verifiedUser()->create();
    $userToken = $user->createToken('test')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $userToken,
    ])->postJson('api/token/create');
    $response->assertStatus(201);
});

it('can\'t delete non-existing token', function () {
    $token = UserToken::factory()->create();
    $tokenID = $token->id + 1;
    $user = User::factory()->create();
    $userToken = $user->createToken('test')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $userToken,
    ])
        ->deleteJson('api/token/delete/' . $tokenID);
    $response->assertStatus(404);
});

it('can\'t delete token if user is not the owner', function () {
    $token = UserToken::factory()->create();
    $user = User::factory()->create();
    $userToken = $user->createToken('test')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $userToken,
    ])
        ->deleteJson('api/token/delete/' . $token->id);
    $response->assertStatus(403);
});

it('can delete token', function () {
    $user = User::factory()->create();
    $token = UserToken::factory()->create([
        'user_id' => $user->id
    ]);
    $userToken = $user->createToken('test')->plainTextToken;
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $userToken,
    ])
        ->deleteJson('api/token/delete/' . $token->id);
    $response->assertStatus(200);
});


it('can\'t access guarder route with no token', function () {

    $response = $this->getJson('api/me');
    $response->assertStatus(401);

});

it('can\'t access guarded route with invalid token', function () {
    $user = User::factory()->create();
    $userToken = UserToken::factory()->create([
        'user_id' => $user->id
    ]);
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $userToken->access_token . '123',
    ])->getJson('api/me');
    $response->assertStatus(401);
});

it('can access guarded route with Bearer token', function () {

    $user = User::factory()->create();
    $userToken = UserToken::factory()->create([
        'user_id' => $user->id
    ]);
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $userToken->access_token,
    ])->getJson('api/me');
    $response->assertStatus(200);
    $user->requests_count++;
    $this->assertEquals($user->toArray(), $response->json());

});

it('can access guarded route with query param', function () {
    $user = User::factory()->create();
    $userToken = UserToken::factory()->create([
        'user_id' => $user->id
    ]);
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->json('GET', 'api/me', [
        'access_token' => $userToken->access_token
    ]);
    $response->assertStatus(200);
});

it('gets logged when accessing guarded route', function () {
    $user = User::factory()->create();
    $userToken = UserToken::factory()->create([
        'user_id' => $user->id
    ]);
    $this->assertEquals(0, $user->requests_count);
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $userToken->access_token,
    ])->getJson('api/me');
    $response->assertStatus(200);
    $user->refresh();
    $this->assertEquals($user->toArray(), $response->json());
    $this->assertCount($user->requests_count, $user->user_request_logs);
});
