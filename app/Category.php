<?php

namespace App;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Category extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $hidden = ['pivot','deleted_at','created_at','updated_at'];
    public static $addRules = [
        'name'=>'required|max:30|min:1',
        'parent'=>'numeric'
    ];

    public static $editRules = [
        'name'=>'max:30|min:1',
        'parent'=>'numeric'
    ];


    public function users(){
        return $this->belongsToMany('App\User','users_categories','category_id','user_id');
    }

    public function products(){
        return $this->belongsToMany('App\Product','products_categories','category_id','product_id')->select(array('id','name'));
    }

    public static function getCategories($user_id,$key = false,$order,$take,$skip = false){
        return  Category::whereHas('users', function($query) use ($user_id,$key){
            self::setQuery($query, $user_id, $key);
        })->orWhereHas('products',function ($query) use ($key){
            $query->where('name', 'LIKE', "%$key%");
        })->take($take)->offset($skip)->orderBy($order, 'asc')->get();
    }

    public static function setQuery($query, $user_id, $key){
        if($key) return $query->where('users.id',$user_id)
                              ->where('name', 'LIKE', "%$key%");
        return $query->where('users.id',$user_id);
    }

    public static function getCategory($user_id,$id){
        return Category::whereHas('users', function($query) use ($user_id,$id){
            $query->where('users.id',$user_id)
                  ->where('categories.id',$id);
        })->first();
    }

    public static function validate($data,$user_id,$key,$id=false){
        if($key==1){
           $rules = self::$addRules;
            $name = $data['name'];
        } else {
            $name = Category::find($id)->name;
            $rules = self::$editRules;
        }
         $validator = Validator::make($data, $rules);
        if(!empty($data['parent'])){
            $parent = $data['parent'] ? $data['parent'] : 0;
            $check = Category::whereHas('users', function($query) use ($user_id,$name,$parent){
                $query->where('users.id',$user_id)->where('categories.name',$name)->where('categories.parent',$parent);
            })->first();
            $check1 = User_Category::where('user_id',$user_id)->where('category_id',$data['parent'])->first();
            if(!$check1) return ApiController::$invalidParentId;
        } else {
            $check = Category::whereHas('users', function($query) use ($user_id,$name){
                $query->where('users.id',$user_id)->where('categories.name',$name)->where('categories.parent',0);
            })->first();
        }
        if($check) return ApiController::$categoryExists;

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $errors;
        }
        return 'success';
    }

    public static function addCategory($data,$user_id){
        $category = new Category();
        $category->name = $data['name'];
        if(!empty($data['parent'])) $category->parent = $data['parent'];
        $category->save();
        $cat_id = $category->id;
        User_Category::saveInUserCategory($user_id,$cat_id);
        return $category;
    }

    public static function updateCategory($data,$category){
        if(!empty($data['name'])) $category->name = $data['name'];
        if(!empty($data['parent']) || $data['parent'] == 0) $category->parent = $data['parent'];
        $category->save();
        return $category;
    }

    public static function deleteCategoryParents($id){
        $categories = Category::where('parent',$id)->get();
        foreach($categories as $category){
            $category->parent = 0;
            $category->save();
        }
        $products = Product_Category::where('category_id',$id)->get();
        foreach($products as $product){
            $product->category_id = null;
            $product->save();
        }
    }

    public static function getCategoryProducts($id){
        return Product::whereHas('categories', function($query) use ($id){
            $query->where('categories.id',$id);
        })->get();
    }
}
