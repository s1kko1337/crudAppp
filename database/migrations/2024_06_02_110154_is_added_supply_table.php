<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplies_status', function (Blueprint $table) {
            $table->id('id_supply');
            $table->boolean('is_added')->default(false);
            $table->timestamps();

            $table->foreign('id_supply')->references('id_supply')->on('supplies')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplies_status');
    }
};
