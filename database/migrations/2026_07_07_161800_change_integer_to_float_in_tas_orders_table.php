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
        Schema::table('tas_orders', function (Blueprint $table) {
            $table->decimal('total_price', 12, 2)->change();     
        });

        Schema::table('tas_order_items', function (Blueprint $table) {
            $table->decimal('price_per_item', 12, 2)->change();
            $table->decimal('totalPrice', 12, 2)->change();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tas_orders', function (Blueprint $table) {
            $table->integer('total_price')->change();
        });
        
        Schema::table('tas_order_items', function (Blueprint $table) {
            $table->integer('price_per_item')->change();
            $table->integer('totalPrice')->change();
        });
    }
};
