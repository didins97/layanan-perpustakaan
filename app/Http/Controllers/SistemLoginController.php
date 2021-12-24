<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SistemLoginController extends Controller
{
    public function register()
    {
        return view('halaman_register');
    }

    public function login()
    {
        return view('halaman_login');
    }

    public function create_register()
    {
        request()->validate([
            "name" => ['required', 'min:5'],
            "password" => ['required', 'min:6'],
            "username" => ['required', 'min:3', 'unique:users']
        ]);

        if (request()->avatar == '') {
            $users = new User;
            $users->name = request()->name;
            $users->email = request()->email;
            $users->username = request()->username;
            $users->password = Hash::make(request()->password);
            $users->avatar = 'default.png';
            $users->remember_token = Str::random(60); 
            $cekLevel = User::all()->count();
            if ($cekLevel != 0) {
                $users->level = "user";
            } else {
                $users->level = "admin";
            }
            echo $users->username;
            $users->save();
        } else {
            $users = new User;
            $avatar = request()->avatar;
            $n_avatar = $avatar->getClientOriginalName();
            $users->name = request()->name;
            $users->email = request()->email;
            $users->username = request()->username;
            $users->password = Hash::make(request()->password);
            $users->avatar = $n_avatar;
            $cekLevel = User::all()->count();
            $users->remember_token = Str::random(60); 
            if ($cekLevel != 0) {
                $users->level = "user";
            } else {
                $users->level = "admin";
            }
            $users->save();
            $avatar->move(public_path('pictures/'), $n_avatar);
        }

        toastr()->success('Akun anda berhasil dibuat');
        return redirect('/login');
    }

    public function verifikasiLogin(Request $request)
    {
        $remember_me = $request->remember ? true : false;
        if(Auth::attempt($request->only('username', 'password'), $remember_me))
    	{
    		return redirect()->route('dashboard');
    	}
        toastr()->error('Maaf username atau password anda salah');
    	return redirect('/login');
    }

    public function prosesLogout()
    {
    	Auth::logout();
    	return redirect('/login');
    }
}
