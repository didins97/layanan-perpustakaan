<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Socialite;

class LGoogleConroller extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
    
            $user = Socialite::driver('google')->user();
     
            $finduser = User::where('google_id', $user->id)->first();
     
            if($finduser){
     
                Auth::login($finduser);
    
                return redirect()->route('dashboard');
     
            }else{
                // $newUser = User::create([
                //     'name' => $user->name,
                //     'email' => $user->email,
                //     'google_id'=> $user->id,
                //     'password' => encrypt('123456dummy')
                // ]);
                $newUser = new User;
                $newUser->name = $user->name;
                $newUser->email = $user->email;
                $newUser->google_id = $user->id;
                $newUser->username = 'didinsibua';
                $newUser->password =  encrypt('123456dummy');
                $newUser->avatar = 'default.png';
                $newUser->remember_token = Str::random(60); 
                $cekLevel = User::all()->count();
                if ($cekLevel != 0) {
                    $newUser->level = "user";
                } else {
                    $newUser->level = "admin";
                }
                $newUser->save();
    
                Auth::login($newUser);
     
                return redirect()->route('dashboard');
            }
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
