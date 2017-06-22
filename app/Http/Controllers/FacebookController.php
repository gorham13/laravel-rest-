<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 21.06.17
 * Time: 15:21
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Library\Facebook;
use App\Models\User;
use Auth;

class FacebookController extends Controller
{
    public function test(Request $request)
    {
        $f = new Facebook();
        $f->postOnWall($request->session()->get('fb_access_token'));


    }

    public function fb_callback(Request $request)
    {
        $f = new Facebook();
        $f->fb_callback($request);
        $fb_user = $f->getUser($request->session()->get('fb_access_token'));

        if(!$user = User::where('email', $fb_user['email'])->first()) {

            $user = new User();
            $user['email'] = $fb_user['email'];
            $user['name'] = $fb_user['name'];
            $user->save();
            Auth::login($user);
            return redirect('/dashboard');
        }
        else
        {
            Auth::login($user);
            return redirect('/dashboard');
        }
    }

    public function fb_auth(Request $request)
    {

        $f = new Facebook();
        return redirect($f->getUri());



    }
}
