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
        Schema::create('contact_list', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name')->nullable();
            $table->string('total_sip')->nullable();
            $table->string('family_org_name')->nullable();
            $table->string('Pan_card')->nullable();
            $table->string('investment')->nullable();
            $table->string('total_investment')->nullable();
            $table->string('kyc_status')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('Aadhar_card')->nullable();
            $table->string('Rms')->nullable();
            $table->string('gender')->nullable();        // e.g. 'male', 'female', 'other'
            $table->date('birthdate')->nullable();       // e.g. '1990-05-01'
            $table->string('relation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_list');
    }
};
