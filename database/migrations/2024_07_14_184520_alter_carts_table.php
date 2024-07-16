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
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->change();
            $table->unsignedBigInteger('shop_id')->nullable()->change();
            $table->string('item_name')->after('item_id');
            $table->string('item_image_name')->after('item_name');
            $table->string('shop_name')->after('shop_id');
            $table->string('message')->after('purchased');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable(false)->change();
            $table->dropColumn('item_name');
            $table->dropColumn('item_image_name');
            $table->dropColumn('message');
            $table->dropColumn('shop_name');
        });
    }
};
