<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Detail extends Model
{
    public $timestamps = false;

    public function users(){
        return $this->belongsTo('App\User','user_id');
    }

    public static function saveCompanyName($user_id, $company_name){
        $obj = new User_Detail();
        $obj->user_id = $user_id;
        $obj->company_name = $company_name;
        $obj->save();
    }

}
