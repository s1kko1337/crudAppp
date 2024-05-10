<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id('id_sale');
            $table->unsignedBigInteger('id_saler');
            $table->unsignedBigInteger('id_product');
            $table->date('sale_date');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('total_price');
            $table->timestamps();
        
            $table->foreign('id_saler')->references('id_saler')->on('sellers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_product')->references('id_product')->on('product')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
