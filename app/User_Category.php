<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Category extends Model
{
    public $timestamps = false;
    protected $table = 'users_categories';

    public static function saveInUserCategory($user_id,$cat_id){
        $user_category = new User_Category();
        $user_category->user_id = $user_id;
        $user_category->category_id = $cat_id;
        $user_category->save();
    }

}
