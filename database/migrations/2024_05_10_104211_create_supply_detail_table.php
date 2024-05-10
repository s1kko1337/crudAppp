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
        Schema::create('supply_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('id_supply');
            $table->unsignedBigInteger('id_product');
            $table->unsignedInteger('quantity');
        
            $table->foreign('id_supply')->references('id_supply')->on('supplies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_product')->references('id_product')->on('product')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_detail');
    }
};
