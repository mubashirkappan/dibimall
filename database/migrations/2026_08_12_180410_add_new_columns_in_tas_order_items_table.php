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
        // These three columns were later also added to the original
        // create_tas_orders_table migration, so on an existing database this
        // migration has already run, while a fresh migrate would double-add them.
        // Guard each column so both paths converge on the same schema.
        Schema::table('tas_order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('tas_order_items', 'unit')) {
                $table->string('unit')->nullable();
            }

            if (! Schema::hasColumn('tas_order_items', 'preparation_preference')) {
                $table->string('preparation_preference')->nullable();
            }

            if (! Schema::hasColumn('tas_order_items', 'item_note')) {
                $table->text('item_note')->nullable();
            }
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
