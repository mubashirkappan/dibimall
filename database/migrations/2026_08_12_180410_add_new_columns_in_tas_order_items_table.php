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
        Schema::table('tas_order_items', function (Blueprint $table) {
            $table->string('unit')->nullable();
            $table->string('preparation_preference')->nullable();
            $table->text('item_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tas_order_items', function (Blueprint $table) {

            $table->dropColumn(['unit', 'preparation_preference', 'item_note']);
        });
    }
};
