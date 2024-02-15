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
            $table->integer('category_count');
            $table->integer('image_count');
            $table->unsignedBigInteger('type_id');
            $table->boolean('delivery');
            $table->integer('km');
            $table->boolean('take_away');
            $table->boolean('top_shop');
            $table->integer('active');
            $table->unsignedBigInteger('place_id');
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('place_id')->references('id')->on('places');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_types');
    }
};
