<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->stateless()->user();
        $user = User::where('provider_id', $getInfo->id)->first();
        if(!$user){
            $newUser = new User;
            $newUser->provider = $provider;
            $newUser->provider_id = $getInfo->id;
            $newUser->name = $getInfo->name;
            $newUser->email = $getInfo->email;
            $newUser->save();
            auth()->login($newUser);
            return redirect('/home');
        } 
        else {
            auth()->login($user);
            return redirect('/home');
        }

    }
}
