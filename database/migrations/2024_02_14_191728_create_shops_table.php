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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('landmark');
            $table->string('country_code');
            $table->string('phone');
            $table->string('email');
            $table->string('logo_name');
            $table->integer('image_count');
            $table->boolean('delivery')->default(false);
            $table->integer('km');
            $table->boolean('take_away')->default(true);
            $table->boolean('top_shop')->default(false);
            $table->integer('active')->default(true);
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('place_id');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('customer_id')->nullable()->comment('owner id in customer table');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict')->onUpdate('cascade');
            $table->string('slug')->nullable();
            $table->integer('item_count')->default('50');
            $table->integer('free_delivery_above')->nullable();
            $table->string('currency');
            $table->string('from')->default('dibimall');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
