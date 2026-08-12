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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('apple_access_token')->nullable();
            $table->text('gmail_access_token')->nullable();
            $table->string('password_reset_token')->nullable();
            $table->smallInteger('user_type')->nullable()->comment('1 for user 2 owner');
            $table->string('whatsapp_number')->nullable();
            $table->unsignedBigInteger('reffered_by')->nullable();
            $table->string('referal_code');
            $table->integer('reward_coin')->nullable()->default(0);
            $table->integer('shop_count')->default(1);
            $table->string('from')->default('dibimall');
            $table->string('timezone')->nullable();

            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
