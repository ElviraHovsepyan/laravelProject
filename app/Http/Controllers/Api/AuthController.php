<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
     * first_name -> required|255
     * last_name -> required
     * */
    public function register(Request $request){
        $data = $request->all();
        $validate = User::validate($data);
        if($validate!='success'){
            $response = apiController::apiValidateFail($validate);
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

    public function notFound(){
        $response = [
           'success'=>'false',
            'message'=>'Sorry, but this page does not exist.',
            'code'=>1000
        ];
        return json_encode($response);
    }
}

