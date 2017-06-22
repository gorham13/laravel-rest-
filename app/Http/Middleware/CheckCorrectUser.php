<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use App\Models\Post;

class CheckCorrectUser
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
        $id = $request->id;
        $user = JWTAuth::parseToken()->toUser();
        $user_id = Post::find($id)->user_id;
        if (!$user->is_admin) {
            if ($user_id != $user->id) {
                return response()->json([], 403);
            }
        }
        return $next($request);
    }
}
