<?php

namespace App\Providers;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Str;

class TokenUserProvider implements UserProvider
{
    private UserToken $token;
    private User $user;

    public function __construct (User $user, UserToken $token) {
        $this->user = $user;
        $this->token = $token;
    }
    public function retrieveById ($identifier): ?Authenticatable
    {
        return $this->user->find($identifier);
    }

    public function retrieveByToken ($identifier, $token): ?Authenticatable
    {
        $token = $this->token->with('user')->where($identifier, $token)->first();

        return $token && $token->user ? $token->user : null;
    }

    public function updateRememberToken (Authenticatable $user, $token) {
        // TODO: Implement updateRememberToken() method.
    }

    public function retrieveByCredentials (array $credentials): ?Authenticatable
    {


        $user = $this->user;

        foreach ($credentials as $credentialKey => $credentialValue) {
            if (!Str::contains($credentialKey, 'password')) {
                $user->where($credentialKey, $credentialValue);
            }
        }
        return $user->first();
    }

    public function validateCredentials (Authenticatable $user, array $credentials): bool
    {
        $plain = $credentials['password'];

        return app('hash')->check($plain, $user->getAuthPassword());
    }
}
