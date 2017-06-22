<?php

namespace App\Http\Controllers;

use App\Events\LoginUser;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class UsersController extends Controller
{


    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
        event(new LoginUser($request->email));
        return response()->json(compact('token'));
    }

    public function getUsers(Request $request)
    {
        $users = User::paginate(10);
        return response()->json($users, 200);
    }



    public function create(Request $request /*, User $user*/){

        $input = $request->all();
        $user = new User();

        if ($user->validateCustom($input, 'rulesForCreate')) {
            return response()->json($user->errors(), 400);
        }

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        return response()->json($user, 200);
    }

    public function update(Request $request, $id){

        $user = User::find($id);
        if(!$user){
            return response()->json(null,404);
        }

        if(!$user->validateCustom($request->all(),'rulesForUpdate')) {
            return response()->json($user->errors(), 400);
        }

        $user->update($request->all());
        return response()->json($user, 200);

    }



    public function get($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([],404);
        }
        return response()->json($user);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([],404);
        }
        $user->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request, User $user)
    {
        $reverseFields = ['DESC', 'ASC'];

        $input = $request->all();

        $q = $request->get('q','');
        $sort = $request->get('sort','');
        $reverse = $request->get('reverse','');
        $gender = $request->get('gender','');
        $limit = $request->get('limit',10);

        if(!in_array($sort, $user->sortFields)){
            $sort = 'created_at';
        }

        if(!in_array($reverse, $reverseFields)){
            $reverse = 'DESC';
        }

        $users = User::where(function($query) use ($q) {
            $query->where('name', 'like', '%'.$q.'%')
                ->orWhere('email', 'like', '%'.$q.'%')
                ->orWhere('country', 'like', '%'.$q.'%')
                ->orWhere('city', 'like', '%'.$q.'%');
        });
            if($gender == 'male'||$gender == 'female') {
                $users = $users
                    ->where(function ($query) use ($gender) {
                    $query->where('gender', $gender);
                });
            }
            $users = $users->orderBy($sort, $reverse)
            ->paginate($limit);

        return response()->json($users, 200);
    }






}
