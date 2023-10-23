<?php

namespace App\Http\Middleware;

use App\Events\CustomTokenUsed;
use App\Models\UserRequestLog;
use App\Models\UserToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogCustomTokenUse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        event(new CustomTokenUsed($request));
        return $next($request);
    }
}
