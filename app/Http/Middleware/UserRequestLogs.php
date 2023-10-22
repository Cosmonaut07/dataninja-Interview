<?php

namespace App\Http\Middleware;

use App\Models\UserRequestLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRequestLogs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->input('access_token');
        UserRequestLog::create([
            'request_method' => $request->method(),
            'request_params' => json_encode($request->all()),
            'user_id' => $request->user()->id,
            'token_id' => $request->user()->user_tokens()->where('access_token', $token)->first()->id,
        ]);
        $request->user()->increment('requests_count');
        return $next($request);
    }
}
