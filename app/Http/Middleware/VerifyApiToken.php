<?php

namespace App\Http\Middleware;

use Closure;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!request()->has('access_token')) {
            return response()->json(['success' => false, 'error_code' => 403, 'message' => 'Неверный токен'], 403);
        }
        return $next($request);
    }
}
