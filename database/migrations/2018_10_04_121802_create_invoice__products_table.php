<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice__products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->integer('discount');
            $table->integer('price');
            $table->integer('product_id');
            $table->integer('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice__products');
    }
}
