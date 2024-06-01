<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers_registered', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable(false);
            $table->unsignedBigInteger('id_saler')->nullable(false);
            $table->timestamps();
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_saler')->references('id_saler')->on('sellers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers_registered');
    }
};
