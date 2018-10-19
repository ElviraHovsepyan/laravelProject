<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';
    public $timestamps = false;

    public function products(){
        return $this->belongsTo('App\Product','product_id');
    }

    public static function saveInStock($product_id,$quantity){
        $stock = new Stock();
        $stock->product_id = $product_id;
        $stock->quantity = $quantity;
        $stock->save();
    }

    public static function updateStock($product_id,$quantity){
        $pr = Stock::where('product_id',$product_id)->first();
        $realQuantity = $pr->quantity - $quantity;
        $pr->quantity = $realQuantity;
        $pr->save();
    }
}
