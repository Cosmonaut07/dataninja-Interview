<?php

namespace App\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class CustomTokenGuard implements Guard
{

    use GuardHelpers;

    private string $inputKey = '';
    private string $storageKey = '';
    private Request $request;

    public function __construct(
        UserProvider $provider,
        Request      $request,
        array        $configuration,
    )
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->inputKey = $configuration['input_key'] ?? 'access_token';
        $this->storageKey = $configuration['storage_key'] ?? 'access_token';
    }


    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $user = $this->provider->retrieveByToken($this->storageKey, $token);
        }

        return $this->user = $user;

    }


    public function validate(array $credentials = []): bool
    {

        if (empty($credentials[$this->inputKey])) {
            return false;
        }

        $credentials = [$this->storageKey => $credentials[$this->inputKey]];
        if ($this->provider->retrieveByCredentials($credentials)) {
            return true;
        }

        return false;
    }


    public function getTokenForRequest()
    {
        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->request->bearerToken();
        }

        return $token;
    }

}
