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
        Schema::table('auth_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            // Rename column
            $table->renameColumn('expires_ac_token', 'expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auth_user', function (Blueprint $table) {
            //
        });
    }
};
