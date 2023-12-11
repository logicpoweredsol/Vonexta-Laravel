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
        Schema::create('user_have_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_services_id')->references('id')->on('organization_services');
            $table->foreignId('organization_id')->references('id')->on('organizations');
            $table->foreignId('service_id')->references('id')->on('services');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_have_services');
    }
};
