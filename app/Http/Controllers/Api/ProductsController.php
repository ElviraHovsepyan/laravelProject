<?php

namespace App\Http\Controllers\Api;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function index(Request $request,$take=false,$skip=false){
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
        $products = Product::getAllProducts($user_id, $key,$order,$take,$skip);
        if(empty($products) || $products == '[]') return ApiController::$noProducts;
        return json_encode(Product::modifyProducts($products,2));
    }

    public function store(Request $request){
        $data = $request->all();
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $validate = Product::validate($data,1,$user_id);
        if($validate!='success'){
            $response = apiController::apiValidateFail($validate);
            return json_encode($response);
        }
        $image = $request->has('image') ? $request->file('image') : false;
        $product = Product::addProduct($data,$image,$user_id);
        return ApiController::apiProductAddSuccess(Product::modifyProducts($product,1));
    }

    public function show(Request $request, $id){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $product = Product::getProduct($user_id,$id);
        if(empty($product)) return ApiController::$productIdError;
        return json_encode(Product::modifyProducts($product,1));
    }

    public function update(Request $request, $id){
        $data = $request->all();
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $product = Product::getProduct($user_id,$id);
        if(empty($product)) return ApiController::$productIdError;
        $validate = Product::validate($data,2,$user_id);
        if($validate!='success'){
            $response = apiController::apiValidateFail($validate);
            return json_encode($response);
        }
        $image = $request->has('image') ? $request->file('image') : false;
        $product = Product::updateProduct($data,$image,$product,$user_id);
        return ApiController::apiProductEditSuccess(Product::modifyProducts($product,1));
    }

    public function destroy(Request $request, $id){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $product = Product::getProduct($user_id,$id);
        if(empty($product)) return ApiController::$productIdError;
        $product->delete();
        return ApiController::$apiProductDelete;
    }

}
