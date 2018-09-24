<?php

namespace App;

use App\Http\Controllers\ApiController;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $incorrectEmail = 'Please check that you have entered your email correctly.';
    public static $incorrectPassword = 'Your password does not match.';
    public static $verifyEmail = 'Please check your Email to verify your account.';
    public static $loginSuccess = 'You have logged in successfully';

    public static function validate($data){
        $rules = [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $errors;
        } else {
            return 'success';
        }
    }

    public static function register($data){
        $vCode = str_random(60);
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $token = Hash::make($email);
        $user = new User();
        $user->name = $username;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->token = $token;
        $user->verified = false;
        $user->verification_code = $vCode;
        $user->save();
//        $mail = sendEmail($username,$vCode);
        return 'success';
    }

    public static function login($data, $api = false){
        $email = $data['email'];
        $password = $data['password'];
        $check = User::where('email',$email)->first();
        if($check){
            $hashedPassword = $check->password;
            if(Hash::check($password, $hashedPassword)){
                if($check->verified==false){
                    if($api){
                        return ApiController::$apiVerifyEmail;
                    }
                    return self::$verifyEmail;
                } else {
                    if($api){
                        $token = $check->token;
                        return ApiController::apiLoginSuccess($token);
                    }
                    $user_id = $check->id;
                    Auth::loginUsingId($user_id);
                    return self::$loginSuccess;
                }
            } else {
                if($api){
                    return ApiController::$apiIncorrectPassword;
                }
                return self::$incorrectPassword;
            }
        } else {
            if($api){
                return ApiController::$apiIncorrectEmail;
            }
            return self::$incorrectEmail;
        }
    }
}


