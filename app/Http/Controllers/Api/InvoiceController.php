<?php

namespace App\Http\Controllers\Api;

use App\Invoice;
use App\Invoice_Product;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
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
        $invoices = Invoice::getInvoices($user_id,$key,$order,$take,$skip);
        if(empty($invoices) || $invoices == '[]') return ApiController::$noInvoices;
        return json_encode($invoices);
    }

    public function store(Request $request){
        $token = $request->header('token');
        $user_id = ApiController::getUserId($token);
        $data = $request->all();
        $validate = Invoice::validate($data,$user_id);
        if($validate != 'success'){
            $response = apiController::apiValidateFail($validate);
            return json_encode($response);
        }
        $invoice = Invoice::saveInvoice($data,$user_id);
        $invoice_id = $invoice->id;
        $total_price = Invoice_Product::saveInvoiceProducts($data,$user_id,$invoice_id);
        Invoice::updatePrice($invoice_id,$total_price);
        return ApiController::invoiceAddSuccess($invoice);
    }

    // products send json format
    //[{"quantity":"5","discount":"10","product_id":"42"}]
}
