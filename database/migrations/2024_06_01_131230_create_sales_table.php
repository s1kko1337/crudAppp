<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id_sale')->primary();
            $table->unsignedBigInteger('id_saler');
            $table->date('sale_date');
            $table->timestamps();
        
            $table->foreign('id_saler')->references('id_saler')->on('sellers')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
