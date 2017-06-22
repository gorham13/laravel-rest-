<?php

namespace App\Http\Middleware;

use JWTAuth;
use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::parseToken()->toUser();

        if(!$user->is_admin)
            return response()->json(null ,403);

        return $next($request);
    }
}
