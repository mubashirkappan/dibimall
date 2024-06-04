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
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'place_id')) {
                $table->dropForeign(['place_id']);
                $table->dropColumn('place_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'place_id')) {
                $table->unsignedBigInteger('place_id');
                $table->foreign('place_id')->references('id')->on('places')->onDelete('restrict')->onUpdate('cascade');
            }
        });
    }
};
