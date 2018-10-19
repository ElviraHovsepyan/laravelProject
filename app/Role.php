<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    public function users(){
        return $this->hasMany('App\User','role_id','id');
    }

    public static function getRoleId(){
        return Role::where('name','user')->first()->id;
    }
}
