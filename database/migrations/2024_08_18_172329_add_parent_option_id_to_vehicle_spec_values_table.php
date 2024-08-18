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
        Schema::table('vehicle_spec_values', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_option_id')->nullable()->after('value'); // Replace 'some_column' with the actual column name you want to place this field after
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_spec_values', function (Blueprint $table) {
            $table->dropColumn('parent_option_id');
        });
    }
};
