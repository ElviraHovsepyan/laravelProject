<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $categories = Category::whereHas('users', function($query) use ($user_id){
            $query->where('users.id',$user_id);
        })->take(12)->get();
        return $categories;
    }

    public function create()
    {
        return view('XXX');
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $data = $request->all();
        $validate = Category::validate($data,$user_id,1);
        if($validate!='success'){
            return view('XXX')->withWarnings($validate);
        }
        Category::addCategory($data,$user_id);
    }

    public function show($id)
    {
        $user_id = Auth::user()->id;
        $category =  Category::getCategory($user_id,$id);
        $arr['category']=$category;
        $catId = $category->id;
        $subCat = Category::whereHas('users', function($query) use ($user_id,$catId){
            $query->where('users.id',$user_id)->where('categories.parent',$catId);
        })->get();
        if($subCat){
            $arr['subCategories'] = $subCat;
        }
        return $arr;
    }

    public function edit($id)
    {
        $user_id = Auth::user()->id;
        $category = Category::getCategory($user_id,$id);
        return view('XXX');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $user_id = Auth::user()->id;
        $data = $request->all();
        $category =  Category::getCategory($user_id,$id);
        $validate = Category::validate($data,$user_id,2);
        if($validate!='success'){
            return view('XXX')->withWarnings($validate);
        }
        Category::updateCategory($data,$category);
        return view('XXX');
    }

    public function destroy($id)
    {
        $user_id = Auth::user()->id;
        $category =  Category::getCategory($user_id,$id);
        $category->delete();
        Category::deleteCategoryParents($id);
        return view('XXX');
    }
}
