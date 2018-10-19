<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Product extends Model
{
    public $timestamps = false;
    protected $table = 'users_products';

    public static function saveInUserProducts($product_id,$user_id){
        $rel = new User_Product();
        $rel->product_id = $product_id;
        $rel->user_id = $user_id;
        $rel->save();
    }
}
