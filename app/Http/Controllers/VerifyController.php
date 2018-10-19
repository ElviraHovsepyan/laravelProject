<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyController extends Controller
{
    public function verify($code){
        $check = User::where('confirmation_token',$code)->first();
        if(!empty($check) && $check->status==0){
                $check->status=1;
                $check->save();
                return view('auth.login')->withWarnings('Your account successfully confirmed');
        }
        else return('Your account already confirmed');
    }
}
