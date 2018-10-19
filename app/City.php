<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    public function clients(){
        return $this->hasMany('App\Client','city_id','id');
    }

    public static function getCity($city_name){
        return City::where('name',$city_name)->first();
    }
}
