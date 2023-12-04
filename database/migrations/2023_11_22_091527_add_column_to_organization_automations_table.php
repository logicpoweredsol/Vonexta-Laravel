<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_automations', function (Blueprint $table) {
            $table->string('automation_name'); // Replace 'new_column_name' with your desired column name and type.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_automations', function (Blueprint $table) {
            $table->dropColumn('automation_name'); // Replace 'new_column_name' with the column you added.
        });
    }
};
