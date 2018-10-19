<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_Category extends Model
{
    public $timestamps = false;
    protected $table = 'products_categories';

    public static function saveInProductCategory($product_id, $category_id=false){
        $obj = new Product_Category();
        $obj->product_id = $product_id;
        if($category_id){
            $obj->category_id = $category_id;
        }
        $obj->save();
    }

    public static function updateCategory($product_id,$category_id){
        $pr_cat = Product_Category::where('product_id',$product_id)->first();
        $pr_cat->category_id = $category_id;
        $pr_cat->save();
    }
}
