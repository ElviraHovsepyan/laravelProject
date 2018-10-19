<?php

namespace App;

use App\Http\Controllers\GoogleGeocoderController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Client extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at','lat','lng'];

    public $timestamps = false;

    public static $addRules = [
        'name'=>'required|max:30',
        'city_id'=>'required|numeric|max:100|exists:cities,id',
        'address'=>'required|string|max:100',
        'note'=>'alpha|max:20'
    ];

    public static $editRules = [
        'name'=>'max:30',
        'city_id'=>'numeric|max:100|exists:cities,id',
        'address'=>'string|max:100',
        'note'=>'alpha|max:20'
    ];

    public function cities(){
        return $this->belongsTo('App\City','city_id');
    }

    public function users(){
        return $this->belongsTo('App\User','user_id');
    }

    public function invoices(){
        return $this->hasMany('App\Invoice','client_id','id');
    }

    public static function validate($data,$key,$user_id){
        if($key==1){
            $rules = self::$addRules;
        } else {
            $rules = self::$editRules;
        }
        if(!empty($data['name'])){
            $name = $data['name'];
            $check = Client::where([['name',$name],['user_id',$user_id]])->get();
            if(count($check) > 0) return 'The name must be unique';
        }
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $errors;
        } else {
            return 'success';
        }
    }

    public static function addClient($data,$user_id){
        $client = new Client;
        $client->name = $data['name'];
        $client->address = $data['address'];
        if(!empty($data['note'])) $client->note = $data['note'];
        $client->user_id = $user_id;
        $client->city_id = $data['city_id'];
        $client->save();
        $client_id = $client->id;
        $geocode_res = GoogleGeocoderController::setGeocode($client_id);
        $client = $geocode_res ? $geocode_res : $client;
        return $client;
    }

    public static function updateClient($data, $client){
        if(!empty($data['name'])) $client->name = $data['name'];
        if(!empty($data['address'])) $client->address = $data['address'];
        if(!empty($data['note'])) $client->note = $data['note'];
        if(!empty($data['city_id'])) $client->city_id = $data['city_id'];
        $client->save();
        $client_id = $client->id;
        $client = GoogleGeocoderController::setGeocode($client_id);
        return $client;
    }

    public static function getClients($user_id,$key = false,$order,$take,$skip = false){
        return Client::whereHas('users', function($query) use ($user_id,$key){
            self::setQuery($query, $user_id, $key);
        })->orWhereHas('cities',function ($query) use ($key){
            $query->where('name', 'LIKE', "%$key%");
        })->orderBy($order, 'asc')->take($take)->offset($skip)->get();
    }

    public static function setQuery($query, $user_id, $key){
        if($key) return   $query->where('users.id',$user_id)
                                ->where('name', 'LIKE', "%$key%")
                                ->orWhere('address', 'LIKE', "%$key%");
        return $query->where('users.id',$user_id);
    }

    public static function getClient($user_id, $id){
        return Client::where('user_id',$user_id)->where('id',$id)->first();
    }
}




