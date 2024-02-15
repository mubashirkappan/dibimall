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
            $table->string('name');
            $table->string('username')->unique()->nullable(); 
            $table->string('email')->unique();
            $table->string('password');
            $table->string('firstname')->nullable(); 
            $table->string('lastname')->nullable(); 
            $table->string('phonenumber')->nullable(); 
            $table->string('apple_access_token')->nullable();
            $table->text('gmail_access_token')->nullable();
            $table->string('password_reset_token')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};