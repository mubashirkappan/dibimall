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
            $table->unsignedBigInteger('reffered_by')->nullable()->after('password');
            $table->string('referal_code')->after('reffered_by')->unique();
            $table->integer('reward_coin')->after('referal_code')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('reffered_by');
            $table->dropColumn('referal_code');
            $table->dropColumn('reward_coin');
        });
    }
};
