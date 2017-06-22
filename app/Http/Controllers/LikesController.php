<?php

namespace App\Http\Controllers;

use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use JWTAuth;

class LikesController extends Controller
{
    public function togle(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        dd($user);
        $post = Post::find($request->post_id);
        if (!$user|| !$post)
            return response()->json(null, 404);


        if($like = Like::where('user_id', $user->id)->where('post_id', $request->post_id)->first())
        {
            $like->delete();
            return response()->json([], 204);
        }
        $like = new Like();

        $like->user_id = $user->id;
        $like->post_id = $request->post_id;

        $like->save();

        return response()->json($like, 200);
    }
}
