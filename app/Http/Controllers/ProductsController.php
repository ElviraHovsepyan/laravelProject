<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $products = Product::whereHas('users', function($query) use ($user_id){
            $query->where('users.id',$user_id);
        })->take(12)->get();
        return $products;
    }

    public function create()
    {
      return view('XXX');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user_id = Auth::user()->id;
        $image = $request->has('image_name') ? $request->file('image_name') : false;
        $validate = Product::validate($data,1,$user_id);
        if($validate!='success'){
            return view('XXX')->withWarnings($validate);
        }
        Product::addProduct($data, $image, $user_id);
        return view('XXX');
    }

    public function show($id)
    {
        $user_id = Auth::user()->id;
        $product = Product::getProduct($user_id,$id);
        if($product) return Product::modifyProducts($product,1);
    }

    public function edit($id)
    {
        $user_id = Auth::user()->id;
        $product = Product::getProduct($user_id,$id);
        return Product::modifyProducts($product,1);

    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user_id = Auth::user()->id;
        $product = Product::getProduct($user_id,$id);
        $validate = Product::validate($data,2,$user_id);
        if($validate!='success'){
            return view('XXX')->withErrors($validate);
        }
        $image = $request->has('image') ? $request->file('image') : false;
        Product::updateProduct($data,$image,$product,$user_id);
        return view('XXX');
    }

    public function destroy($id)
    {
        $user_id = Auth::user()->id;
        $product = Product::getProduct($user_id,$id);
        $product->delete();
    }
}


