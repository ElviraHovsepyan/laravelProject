<?php

namespace App;

use App\Http\Controllers\Api\ApiController;
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

    public static $incorrectEmail = 'Invalid Email or password';
    public static $verifyEmail = 'Please check your Email to verify your account.';
    public static $loginSuccess = 'You have logged in successfully';

    public function categories(){
        return $this->belongsToMany('App\Category','users_categories','user_id','category_id');
    }

    public function products(){
        return $this->belongsToMany('App\Product','users_productss','user_id','product_id');
    }

    public function roles(){
        return $this->belongsTo('App\Role','role_id');
    }

    public function user_details(){
        return $this->hasOne('App\User_Detail','user_id');
    }

    public function clients(){
        return $this->hasMany('App\Client','user_id','id');
    }

    public function invoices(){
        return $this->hasMany('App\Invoice','user_id','id');
    }

    public static function validate($data){
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'company_name' => 'required|string|min:2|max:255'
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
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $password = $data['password'];
        $token = Hash::make($email);
        $confirmation_token = str_random(60);
        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->api_token = $token;
        $user->status = 0;
        $user->role_id = Role::getRoleId();
        $user->confirmation_token = $confirmation_token;
        $user->save();
        $user_id = $user->id;
        $company_name = $data['company_name'];
        User_Detail::saveCompanyName($user_id, $company_name);
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
                if($check->status==0){
                    if($api) return ApiController::$apiVerifyEmail;
                    return 'verify';
                } else {
                    if($api){
                        $token = $check->api_token;
                        return ApiController::apiLoginSuccess($token);
                    }
                    $user_id = $check->id;
                    if(!empty($data['remember'])) Auth::loginUsingId($user_id, true);
                    else Auth::loginUsingId($user_id);
                    return self::$loginSuccess;
                }
            } else {
                if($api) return ApiController::$apiIncorrectPassword;
                return self::$incorrectEmail;
            }
        } else {
            if($api) return ApiController::$apiIncorrectEmail;
            return self::$incorrectEmail;
        }
    }
}

