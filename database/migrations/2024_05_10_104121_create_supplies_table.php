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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id('id_supply');
            $table->unsignedBigInteger('id_supplier');
            $table->date('supply_date');
            $table->unsignedInteger('quantity_products');
            $table->unsignedBigInteger('total_price');
            $table->timestamps();
        
            $table->foreign('id_supplier')->references('id_supplier')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
