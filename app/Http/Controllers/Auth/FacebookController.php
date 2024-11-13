<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            // $user = Socialite::driver('facebook')->user();
            // $finduser = User::where('facebook_id', $user->id)->first();

            // if ($finduser) {
            //     //Auth::login($finduser);
            //     //return redirect()->intended('dashboard');
            // } else {
            //     $newUser = User::create([
            //         'name' => $user->name,
            //         'email' => $user->email,
            //         'facebook_id'=> $user->id,
            //         'password' => encrypt('123456dummy')
            //     ]);

            //     //Auth::login($newUser);
            //     //return redirect()->intended('dashboard');
            // }

            $user = Socialite::driver('facebook')->user();
 
            // OAuth 2.0 providers...
            $token = $user->token;
            $refreshToken = $user->refreshToken;
            $expiresIn = $user->expiresIn;
        
            // OAuth 1.0 providers...
            $token = $user->token;
            $tokenSecret = $user->tokenSecret;
        
            // All providers...
            $user->getId();
            $user->getNickname();
            $user->getName();
            $user->getEmail();
            $user->getAvatar();

            echo "tok:" . $token . " sec:" . $tokenSecret;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}