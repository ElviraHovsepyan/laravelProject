<?php

namespace App\Http\Controllers;

use App\User;
//use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerView(){
        return view('register');
    }

    public function loginView(){
        return view('login');
    }

    public function register(Request $request){
        $data = $request->all();
        $validate = User::validate($data);
        if($validate!='success'){
            return view('register')->withWarnings($validate);
        }
        User::register($data);
        return view('register')->withWarnings(User::$verifyEmail);
    }

    public function login(Request $request){
        $data = $request->all();
        $login = User::login($data);
        return view('login')->withWarnings($login);
    }
}
