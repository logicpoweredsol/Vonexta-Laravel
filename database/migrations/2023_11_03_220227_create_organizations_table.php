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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            // $table->foreign('superadmin_id')->references('id')->on('users');
            $table->string("name",100);
            $table->string("phone",20);
            $table->string("address",250);
            $table->string("address2",250);
            $table->string("city",100);
            $table->string("state",50);
            $table->string("zip",10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
