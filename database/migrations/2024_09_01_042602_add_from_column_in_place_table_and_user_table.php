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
            $table->string('from')->default('dibimall');
        });
        Schema::table('places', function (Blueprint $table) {
            $table->string('from')->default('dibimall');
        });
        Schema::table('tas_orders', function (Blueprint $table) {
            $table->dropColumn('is_completed');
            $table->enum('status', ['pending', 'deliverd'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('from');
        });
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('from');
        });
        Schema::table('tas_orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('is_completed');
        });
    }
};
