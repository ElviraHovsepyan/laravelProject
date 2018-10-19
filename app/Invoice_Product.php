<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice_Product extends Model
{
    public $timestamps = false;
    protected $table = 'invoice__products';

    public function invoices(){
        return $this->belongsTo('App\Invoice','invoice_id');
    }

    public function products(){
        return $this->belongsTo('App\Product','product_id');
    }

    public static function saveInvoiceProducts($data,$user_id,$invoice_id){
        $products = $data['products'];
        $products = json_decode($products, true);
        $total_price = 0;
        foreach ($products as $product){
            if(!empty($product->quantity)){
                $quantity = $product['quantity'];
            } else {
                $quantity = 1;
            }
            $product_id = $product['product_id'];
            if(!empty($product['discount'])){
                $discount = $product['discount'];
            } else {
                $discount = 0;
            }
            $db_product = Product::whereHas('users', function($query) use ($user_id,$product_id){
                $query->where('users.id',$user_id)->where('products.id',$product_id);
            })->first();
            $real_price = ($db_product->price)/100;
            $price = ($real_price - ($real_price*($discount/100)))*$quantity;
            $inv_pr = new Invoice_Product();
            $inv_pr->quantity = $quantity;
            $inv_pr->product_id = $product_id;
            $inv_pr->discount = $discount;
            $inv_pr->price = $price*100;
            $inv_pr->invoice_id = $invoice_id;
            $inv_pr->save();
            Stock::updateStock($product_id,$quantity);
            $total_price += $price;
        }
        return $total_price;
    }
}
