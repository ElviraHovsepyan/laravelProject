<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request, $take=false,$skip=false){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        if(!empty($request->header('search'))){
            $key = $request->header('search');
        } else {
            $key = false;
        }
        if(!empty($request->header('order'))){
            $order = $request->header('order');
        } else {
            $order = "id";
        }
        if(!$take) $take = 1000;
        $categories = Category::getCategories($user_id,$key,$order,$take,$skip);
        if(empty($categories) || $categories == '[]') return ApiController::$noCategories;
        return json_encode($categories);
    }

    public function store(Request $request){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $data = $request->all();
        $validate = Category::validate($data,$user_id,1);
        if($validate!='success'){
            $response = apiController::apiValidateFail($validate);
            return json_encode($response);
        }
        $category = Category::addCategory($data,$user_id);
        return ApiController::categoryAddSuccess($category);
    }

    public function show(Request $request, $id){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $category =  Category::getCategory($user_id,$id);
        if($category){
            $arr['category']=$category;
            $catId = $category->id;
                $subCat = Category::whereHas('users', function($query) use ($user_id,$catId){
                    $query->where('users.id',$user_id)->where('categories.parent',$catId);
                })->get();
                if($subCat){
                    $arr['subCategories'] = $subCat;
                }
                return json_encode($arr);
        } else {
            return ApiController::$invalidCatId;
        }
    }

    public function update(Request $request, $id){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $data = $request->all();
        $category =  Category::getCategory($user_id,$id);
        if(empty($category)) return ApiController::$invalidCatId;
        $validate = Category::validate($data,$user_id,2,$id);
        if($validate!='success'){
            $response = apiController::apiValidateFail($validate);
            return json_encode($response);
        }
        $categoryNew = Category::updateCategory($data,$category);
        return ApiController::categoryEditSuccess($categoryNew);
    }

    public function destroy(Request $request, $id){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $category =  Category::getCategory($user_id,$id);
        if(empty($category)) return ApiController::$invalidCatId;
        $category->delete();
        Category::deleteCategoryParents($id);
        return ApiController::$categoryDelete;
    }

    public function category_products(Request $request, $id){
        $products = Category::getCategoryProducts($id);
        if(!$products || $products=='[]') return ApiController::$noProductsInCat;
        return json_encode($products);
    }
}
