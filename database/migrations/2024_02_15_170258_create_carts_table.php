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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->nullable()->references('id')->on('items')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('count');
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('dibi_price', 12, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->boolean('purchased')->default(false);
            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->nullable()->references('id')->on('shops')->onDelete('restrict')->onUpdate('cascade');
            $table->string('item_name')->nullable();
            $table->string('item_image_name')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
