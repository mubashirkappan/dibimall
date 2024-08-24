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
        Schema::create('tas_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained();
            $table->string('user_name');
            $table->integer('user_phone_number');
            $table->text('address');
            $table->integer('total_price');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
        Schema::create('tas_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tas_order_id')->constrained();
            $table->string('name');
            $table->integer('price_per_item');
            $table->integer('quantity');
            $table->integer('totalPrice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tas_order_items');
        Schema::dropIfExists('tas_orders');
    }
};
