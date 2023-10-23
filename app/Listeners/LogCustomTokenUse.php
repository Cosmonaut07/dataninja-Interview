<?php

namespace App\Listeners;

use App\Events\CustomTokenUsed;
use App\Models\UserRequestLog;
use App\Models\UserToken;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCustomTokenUse
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CustomTokenUsed $event): void
    {
        $request = $event->request;
        $token = $request->bearerToken() ?? $request->input('access_token');
        UserRequestLog::create([
            'request_method' => $request->method(),
            'request_params' => $request->all(),
            'user_id' => $request->user()->id,
            'token_id' => UserToken::where('access_token', $token)->first()->id
        ]);
        $request->user()->increment('requests_count');

    }
}
