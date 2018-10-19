<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public static $apiIncorrectEmail = [
        'success'=>'false',
        'message'=> 'Please insert correct email.',
        'code'=>1001
    ];

    public static $apiIncorrectPassword = [
        'success'=>'false',
        'message'=> 'Your password does not match.',
        'code'=>1002
    ];

    public static $apiVerifyEmail = [
        'success'=>'false',
        'message'=> 'Please check your Email to verify your account.',
        'code'=>1003
    ];

    public static $apiTokenError = [
        'success'=>'false',
        'message'=>'Please enter correct API token',
        'code'=>1004
    ];

    public static $apiVerifyEmailRegister = [
        'success'=>'true',
        'message'=>'Please check your Email to verify your account',
        'code'=>1006
    ];

    public static $noProducts = [
        'success'=>'true',
        'message'=>'There are no products yet.',
        'code'=>2001
    ];

    public static $apiProductDelete = [
        'success'=>'true',
        'message'=>'Product successfully deleted.',
        'code'=>2009
    ];

    public static $productIdError = [
        'success'=>'false',
        'message'=>'Please insert correct product Id',
        'code'=>2008
    ];

    public static $noCategories = [
        'success'=>'false',
        'message'=>'There are no categories yet.',
        'code'=>3001
    ];

    public static $invalidParentId = 'Please insert a valid parent Id.';

    public static $categoryExists = 'The category already exists. Change the name or the parent.';

    public static $invalidCatId = [
        'success'=>'false',
        'message'=>'Please insert a valid category Id.',
        'code'=>3003
    ];

    public static $noProductsInCat = [
        'success'=>'false',
        'message'=>'There are no products in this category.',
        'code'=>3004
    ];

    public static $categoryDelete = [
        'success'=>'true',
        'message'=>'Category successfully deleted.',
        'code'=>3009
    ];

    public static $noClients = [
        'success'=>'false',
        'message'=>'There are no clients yet.',
        'code'=>4001
    ];

    public static $invalidClientId = [
        'success'=>'false',
        'message'=>'Please insert a valid client Id.',
        'code'=>4003
    ];

    public static $clientDelete = [
        'success'=>'true',
        'message'=>'Client successfully deleted.',
        'code'=>4009
    ];

    public static $noInvoices = [
        'success'=>'false',
        'message'=>'There are no invoices yet.',
        'code'=>5001
    ];

    public static function addClientSuccess($param){
        return [
            'success'=>'true',
            'message'=>'Client successfully added.',
            'client'=>$param,
            'code'=>4007
        ];
    }

    public static function clientEditSuccess($param){
        return [
            'success'=>'true',
            'message'=>'Client successfully updated.',
            'client'=>$param,
            'code'=>4007
        ];
    }

    public static function apiValidateFail($param){
        return [
            'success'=>'false',
            'message'=> $param,
            'code'=>1005
        ];
    }

    public static function apiLoginSuccess($param){
        return [
            'token'=>$param,
            'success'=>'true',
            'code'=>1007
        ];
    }

    public static function apiProductAddSuccess($param){
        return [
            'success'=>'true',
            'message'=>'Product successfully added.',
            'product'=>$param,
            'code'=>2007
        ];
    }

    public static function apiProductEditSuccess($param){
        return [
            'success'=>'true',
            'message'=>'Product successfully updated.',
            'product'=>$param,
            'code'=>2007
        ];
    }

    public static function categoryAddSuccess($param){
        return [
            'success'=>'true',
            'message'=>'Category successfully added.',
            'category'=>$param,
            'code'=>3007
        ];
    }

    public static function categoryEditSuccess($param){
        return [
            'success'=>'true',
            'message'=>'Category successfully updated.',
            'category'=>$param,
            'code'=>3007
        ];
    }

    public static function invoiceAddSuccess($param){
        return [
            'success'=>'true',
            'message'=>'Invoice successfully added.',
            'invoice'=>$param,
            'code'=>5007
        ];
    }

    public static function checkToken($token){
        $check = User::where('api_token',$token)->first();
        if(!$check){
            return self::$apiTokenError;
        } else {
            return 'success';
        }
    }

    public static function getUserId($token){
        $user = User::where('api_token',$token)->first();
        return $user->id;
    }
}
