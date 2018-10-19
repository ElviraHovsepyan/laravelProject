<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Invoice extends Model
{
    public $timestamps = false;

    public static $rules = [
        'invoice_nr'=>'required|string|max:255',
        'client_id'=>'required|numeric|max:255|exists:clients,id',
        'created_at'=>'date_format:Y-m-d|max:50',
        'products'=>'required|json'
    ];

    public static $productRules = [
        'product_id'=>'required|numeric|exists:products,id',
        'discount'=>'numeric|max:100',
        'quantity'=>'numeric|max:255'
    ];

    public function clients(){
        return $this->belongsTo('App\Client','client_id');
    }

    public function users(){
        return $this->belongsTo('App\User','user_id');
    }

    public function invoice_products(){
        return $this->hasMany('App\Invoice_Product','invoice_id','id');
    }

    public static function getInvoices($user_id,$key = false, $order, $take,$skip = false){

        $q = Invoice::with('invoice_products.products')
            ->whereHas('users',function ($query) use ($user_id,$key){
            self::setQuery($query, $user_id, $key);
        });
        return self::invoicesSearch($q, $key)->orderBy($order, 'asc')->take($take)->offset($skip)->get();
    }

    public static function invoicesSearch($query, $key){
        if(!$key) return $query;
        else return $query->orWhereHas('clients',function ($query) use ($key){
            $query->where('clients.name', 'LIKE', "%$key%")
                  ->orWhere('clients.address', 'LIKE', "%$key%");
        })
            ->orWhereHas('invoice_products',function ($query) use ($key){
                $query->where('invoice__products.price', 'LIKE', "%$key%")
                      ->orWhere('invoice__products.discount', 'LIKE', "%$key%");
            })
            ->orWhereHas('invoice_products.products',function ($query) use ($key){
                $query->where('name', 'LIKE', "%$key%")
                      ->orWhere('price', 'LIKE', "%$key%")
                      ->orWhere('description', 'LIKE', "%$key%")
                      ->orWhere('code', 'LIKE', "%$key%");
            });
    }

    public static function setQuery($query, $user_id, $key){
        if($key) return $query->where('users.id',$user_id)
            ->where('invoice_nr', 'LIKE', "%$key%")
            ->orWhere('price', 'LIKE', "%$key%");
        return $query->where('users.id',$user_id);
    }

    public static function validate($data,$user_id){
        $rules = self::$rules;
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $errors;
        }
        $inv_nr = $data['invoice_nr'];
        $check = Invoice::where([['invoice_nr',$inv_nr],['user_id',$user_id]])->first();
        if($check) return 'The invoice number must be unique';
        $client_id = $data['client_id'];
        $check = Client::where([['user_id',$user_id],['id',$client_id]])->first();
        if(!$check) return 'The client_id is invalid';
        $year = Carbon::parse($data['created_at'])->year;
        if($year < 2015) return 'The date should be up from 2015';
        $products = $data['products'];
        $val = self::validateProducts($products,$user_id);
        if($val != 'success'){
            return $val;
        }
        return 'success';
    }

    public static function validateProducts($products,$user_id){
        $products = json_decode($products);
        $rules = self::$productRules;
        $id_errors = [];
        $quantity_errors = [];
        foreach ($products as $product) {
            if(!empty($product->quantity)) $data['quantity'] = $product->quantity;
            $data['product_id']  = $product->product_id;
            if(!empty($product->discount)) $data['discount'] = $product->discount;
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return $errors;
            }
            $product_id = $product->product_id;
            if(!empty($product->quantity)){
                $quantity = $product->quantity;
            } else {
                $quantity = 1;
            }
            $check1 = Product::whereHas('users', function($query) use ($user_id,$product_id){
                $query->where('users.id',$user_id)->where('products.id',$product_id);
            })->first();
            if(!$check1) {
                $id_errors[] = 'Product Id '.$product_id.' is incorrect';
            }
            $check = Stock::where('product_id',$product_id)->first();
            if($check->quantity < $quantity){
                $quantity_errors[] = 'There are not enough products in the stock.Product Id: '.$product_id;
            }
        }
        if(!empty($id_errors)) return $id_errors;
        if(!empty($quantity_errors)) return $quantity_errors;
        return 'success';
    }

    public static function saveInvoice($data,$user_id){
        $invoice = new Invoice();
        $invoice->invoice_nr = $data['invoice_nr'];
        $invoice->client_id = $data['client_id'];;
        $invoice->price = 1;
        $invoice->user_id = $user_id;
        if(!empty($data['created_at'])){
            $date = $data['created_at'];
            $date = Carbon::parse($date);
            $date = $date->year.'-'.$date->format('m').'-'.$date->day;
        } else {
            $date = Carbon::now()->toDateString();
        }
        $invoice->created_at = $date;
        $invoice->save();
        return $invoice;
    }

    public static function updatePrice($invoice_id,$total_price){
        $inv = Invoice::find($invoice_id);
        $inv->price = $total_price*100;
        $inv->save();
    }
}

