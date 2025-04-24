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
        Schema::create('auth_user', function (Blueprint $table) {
            $table->id();
            $table->string('userName')->nullable();
            $table->string('email')->nullable();
            $table->string('access_token')->nullable();
            $table->string('expires_ac_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('refresh_token_expires')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_user');
    }
};
