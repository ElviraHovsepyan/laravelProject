<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerView(){
        return view('auth.register');
    }

    public function loginView(){
        return view('auth.login');
    }

    public function register(Request $request){
        $data = $request->all();
        $validate = User::validate($data);
        if($validate!='success'){
            return view('auth.register')->withWarnings($validate);
        }
        User::register($data);
        return view('auth.register_verify');
    }

    public function login(Request $request){
        $data = $request->all();
        $login = User::login($data);
        if($login == 'verify'){
            return view('auth.register_verify');
        }
        return view('auth.login')->withWarnings($login);
    }

    public function logout(){
        Auth::logout();
        return view('auth.login');
    }
}
