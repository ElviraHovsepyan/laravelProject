<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Product extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at','created_at','updated_at'];

    public function users(){
        return $this->belongsToMany('App\User','users_products','product_id','user_id');
    }

    public function categories(){
        return $this->belongsToMany('App\Category','products_categories','product_id','category_id')->select(['categories.id', 'categories.name',]);
    }

    public function stock(){
        return $this->hasOne('App\Stock');
    }

    public function invoice_products(){
        return $this->hasMany('App\Invoice_Product','product_id','id');
    }

    public static $addRules = [
        'name'=>'required|max:30|min:1',
        'description'=>'max:255',
        'code'=>'required|max:100|min:1',
        'price'=>'required|numeric|min:1',
        'unit'=>'alpha|max:20',
        'image'=>'image|max:10000',
        'category_id'=>'numeric',
        'quantity' => 'required|numeric|max:999999',
    ];

    public static $editRules = [
        'name'=>'max:30|min:1',
        'description'=>'max:255',
        'code'=>'max:100|min:1',
        'price'=>'numeric|min:1',
        'unit'=>'alpha|max:20',
        'image'=>'image|max:10000',
        'category_id'=>'numeric',
        'quantity' =>'numeric|max:999999'
    ];

    public static function validate($data,$key,$user_id){
        if($key==1){
            $rules = self::$addRules;
        } else {
            $rules = self::$editRules;
        }
        $validator = Validator::make($data, $rules);
        if(!empty($data['category_id'])){
            $id = $data['category_id'];
            $check = Category::getCategory($user_id,$id);
            if(!$check){
                return 'Please enter a valid category id';
            }
        }
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $errors;
        } else {
            return 'success';
        }
    }

    public static function addProduct($data,$image,$user_id){
        $quantity = $data['quantity'];
        $product = new Product();
        $product->name = $data['name'];
        if(!empty($data['description'])) $product->description = $data['description'];
        if(!empty($data['unit']))$product->unit = $data['unit'];
        $product->code = $data['code'];
        $product->price = $data['price']*100;
        $product->image = self::saveImage($image,$user_id);
        $product->save();
        $product_id = $product->id;
        if(!empty($data['category_id'])){
            $category_id = $data['category_id'];
        } else {
            $category_id = false;
        }
        Product_Category::saveInProductCategory($product_id, $category_id);
        Stock::saveInStock($product_id,$quantity);
        User_Product::saveInUserProducts($product_id,$user_id);
        return $product;
    }

    public static function saveImage($image,$user_id){
        if($image) {
            $name =  $image->getClientOriginalName();
            $name = explode('.',$name);
            $ext = end($name);
            $name = $name[0].Carbon::now()->timestamp;
            $image->move('product_images/'.$user_id, $name.'.'.$ext);
            $pic = $name.'.'.$ext;
            return $pic;
        } else {
            $pic = 'ourImage.jpg';
            return $pic;
        }
    }

    public static function updateProduct($data,$image,$product,$user_id){
        if(!empty($data['name'])) $product->name = $data['name'];
        if(!empty($data['description'])) $product->description = $data['description'];
        if(!empty($data['code'])) $product->code = $data['code'];
        if(!empty($data['price'])) $product->price = $data['price']*100;
        if(!empty($data['unit'])) $product->unit = $data['unit'];
        if(!empty($data['image'])) $product->image = self::saveImage($image,$user_id);
        $product->save();
        if(!empty($data['category_id'])) {
            $product_id = $product->id;
            $category_id = $data['category_id'];
            Product_Category::updateCategory($product_id,$category_id);
        }
        $id = $product->id;
        $product = Product::getProduct($user_id,$id);
        return $product;
    }

    public static function getAllProducts($user_id, $keyword = null, $order, $take, $skip = false){
        return Product::whereHas('users', function($query) use ($user_id, $keyword){
            self::setQuery($query, $user_id, $keyword);
        })->take($take)->offset($skip)->orderBy($order, 'asc')->get();
    }

    public static function setQuery($query, $user_id, $keyword){
        if(!$keyword) return $query->where('user_id', $user_id);
        else return $query->where('user_id', $user_id)
                          ->where('name', 'LIKE', "%$keyword%")
                          ->orWhere('price', 'LIKE', "%$keyword%");
    }

    public static function getProduct($user_id,$id){
        return Product::with('categories')->whereHas('users', function($query) use ($user_id,$id){
            $query->where('users.id',$user_id)->where('products.id',$id);
        })->first(['products.id','products.name','products.description','products.code','products.price','products.unit','products.image']);
    }

    public static function modifyProducts($products,$key){
        if($key == 1){
            $products->price = $products->price/100;
        } else {
            foreach ($products as $product){
                $product->price = $product->price/100;
            }
        }
        return $products;
    }
}


