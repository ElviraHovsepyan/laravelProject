<?php

namespace App\Http\Controllers\Api;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index(Request $request,$take = false,$skip = false){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        if(!empty($request->header('search'))){
            $key = $request->header('search');
        } else {
            $key = false;
        }
        if(!empty($request->header('order'))){
            $order = $request->header('order');
        } else {
            $order = "id";
        }
        if(!$take) $take = 1000;
        $clients = Client::getClients($user_id,$key,$order,$take,$skip);
        if(empty($clients) || $clients == '[]') return ApiController::$noClients;
        return json_encode($clients);
    }

    public function store(Request $request){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $data = $request->all();
        $validate = Client::validate($data,1,$user_id);
        if($validate!='success'){
            $response = apiController::apiValidateFail($validate);
            return json_encode($response);
        }
        $client = Client::addClient($data,$user_id);
        return ApiController::addClientSuccess($client);
    }

    public function show(Request $request,$id){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $client = Client::getClient($user_id, $id);
        if(!$client) return ApiController::$invalidClientId;
        return json_encode($client);
    }

    public function update(Request $request,$id){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $data = $request->all();
        $client = Client::getClient($user_id, $id);
        if(!$client) return ApiController::$invalidClientId;
        $validate = Client::validate($data,2,$user_id);
        if($validate!='success'){
            $response = apiController::apiValidateFail($validate);
            return json_encode($response);
        }
        $clientNew = Client::updateClient($data,$client);
        return ApiController::clientEditSuccess($clientNew);
    }

    public function destroy(Request $request,$id){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $client = Client::getClient($user_id, $id);
        if(!$client) return ApiController::$invalidClientId;
        $client->delete();
        return ApiController::$clientDelete;
    }
}


