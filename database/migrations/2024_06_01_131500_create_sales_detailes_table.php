<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_sale');
            $table->unsignedBigInteger('id_product');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('total_price');
            $table->timestamps();
        
            $table->foreign('id_sale')->references('id_sale')->on('sales')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_product')->references('id_product')->on('product')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_details');
    }
};
