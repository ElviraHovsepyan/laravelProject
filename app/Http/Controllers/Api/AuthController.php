<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
     * first_name -> required|255
     * last_name -> optional
     * */
    public function register(Request $request){
        $data = $request->all();
        $validate = User::validate($data);
        if($validate!='success'){
            $response = [
                'success'=>'false',
                'message'=> $validate,
                'code'=>1005
            ];
            return json_encode($response);
        }
        User::register($data);
        $response = ApiController::$apiVerifyEmailRegister;
        return json_encode($response);
    }

    public function login(Request $request){
        $data = $request->all();
        $api = 1;
        $login = User::login($data,$api);
        return json_encode($login);
    }
}

