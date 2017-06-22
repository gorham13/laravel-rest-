<?php

namespace App\Http\Controllers;
use App\Http\Library\CustomGuzlle;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use JWTAuth;


class PostsController extends Controller
{

    public function guzzleTesting()
    {
        $client = new CustomGuzlle();
        $client->postRequest();
    }

    public function create(Request $request, Post $post)
    {
        $input = $request->all();
        $user = JWTAuth::parseToken()->toUser();

        if (!$post->validateCustom($request->all(), 'rulesForCreate'))
            return response()->json($post->errors(), 400);

        if (!$user->is_admin) {
            $input['user_id'] = $user->id;
        }

        $post = Post::create($input);
        return response()->json($post, 200);

    }

    public function update(Request $request, $id, Post $post)
    {
        $user = JWTAuth::parseToken()->toUser();

        if(!$post->validateCustom($request->all(), 'rulesForUpdate'))
            return response()->json($post->errors(), 400);

        $post = Post::where('id', $id)->first();
        $input = $request->all();

        if(!$post)
            return response()->json([],404);

        if (!$user->is_admin) {
            $post->user_id = $user->id;
        }

        $post->title = $request->get('title','');
        $post->article = $request->get('article','');
        $post->image = $request->get('image','');
        $post->save();

        return response()->json($post, 200);
    }

    public function setTags($id, Request $request)
    {
        $tags_ids = [];
        if (!$post = Post::find($id)) {
            return response()->json([], 400);
        }
        foreach($request->tags as $tag)
        {
            if($tag = Tag::where('name', $tag)->first())
                $tag_ids[] = $tag->id;
        }
        Post::find($id)->tags()->sync($tag_ids);
        return response()->json($tag_ids ,200);
    }

    public function get($id)
    {
        //withCount('likes')

        $post = Post::with('tags')->select('posts.*')
            ->selectRaw("count(l.id) as likes_count")
            ->leftJoin('likes as l','l.post_id','posts.id')
            ->groupBy('posts.id')
            ->find($id);


        if(!$post){
            return response()->json([],404);
        }

       // $post['likes'] = Like::with('user')->where('post_id', $id)->get();

        return response()->json($post);
    }

    public function search(Request $request, Post $post)
    {

        $reverseFields = ['DESC', 'ASC'];

        $q = $request->get('q','');
        $user_id = $request->get('user_id','');
        $sort = $request->get('sort','');
        $reverse = $request->get('reverse','');
        $limit = $request->get('limit',10);


        if(!in_array($sort, $post->sortFields)){
            $sort = 'created_at';
        }

        if(!in_array($reverse, $reverseFields)){
            $reverse = 'DESC';
        }

        $posts = Post::select('posts.id','posts.title');

        if($q) {
            $posts = $posts
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where(function($query) use ($q) {
                $query->where('posts.title', 'like', '%'.$q.'%')
                    ->orWhere('posts.article', 'like', '%'.$q.'%')
                    ->orWhere('users.email', 'like', '%'.$q.'%')
                    ->orWhere('users.name', 'like', '%'.$q.'%');
            });
        }

        if($user_id) {
            $posts = $posts->where('user_id', $user_id);
        }

        $posts = $posts->where('posts.published', 1)
            ->orderBy('posts.'.$sort, $reverse)
            ->paginate($limit);

        return response()->json($posts, 200);
    }

    public function autocomplete(Request $request)
    {

        $q = $request->get('q','');

        $tags = Tag::select('tags.*')
        ->selectRaw("count(t.tag_id) as tags_count")
        ->leftJoin('relation_tags_post as t','t.tag_id','tags.id')
        ->groupBy('tags.id')
        ->where('tags.name', 'like', $q.'%')->get();


        return response()->json($tags);
    }



//{
//   'id',
//    'name',
//    'count'
//}
}
