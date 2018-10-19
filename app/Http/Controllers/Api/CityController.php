<?php

namespace App\Http\Controllers\Api;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function index(Request $request){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $cities = City::all();
        return json_encode($cities);
    }
}
