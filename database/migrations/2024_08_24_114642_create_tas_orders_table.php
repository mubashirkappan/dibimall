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
            $table->string('user_phone_number');
            $table->text('address');
            $table->decimal('total_price', 12, 2);

            $table->enum('status', ['pending', 'deliverd'])->default('pending');
            $table->string('delivery_time')->nullable();

            $table->timestamps();
        });
        Schema::create('tas_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tas_order_id')->constrained();
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price_per_item', 12, 2);
            $table->decimal('totalPrice', 12, 2);
            $table->string('unit')->nullable();
            $table->string('preparation_preference')->nullable();
            $table->text('item_note')->nullable();
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
