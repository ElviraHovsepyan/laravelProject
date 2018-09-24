<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function verify($code){
        $check = User::where('verification_code',$code)->first();
        if(!empty($check) && $check->verified==false){
                $check->verified=true;
                $check->save();
                return 'success';
        }
    }
}
