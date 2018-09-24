<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public static $apiIncorrectEmail = [
        'success'=>'false',
        'message'=> 'Please check that you have entered your email correctly.',
        'code'=>1001
    ];
    public static $apiIncorrectPassword = [
        'success'=>'false',
        'message'=> 'Your password does not match.',
        'code'=>1002
    ];
    public static $apiVerifyEmail = [
        'success'=>'false',
        'message'=> 'Please check your Email to verify your account.',
        'code'=>1003
    ];
    public static $apiVerifyEmailRegister = [
        'success'=>'true',
        'message'=>'Please check your Email to verify your account',
        'code'=>1006
    ];
    public static function apiLoginSuccess($param){
        return [
        'token'=>$param,
        'success'=>'true',
        'code'=>1007
        ];
    }
}
