<?php

use App\Models\Specification;
use App\Models\Vehicle;
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
        Schema::create('vehicle_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Vehicle::class)->nullable();
            $table->foreignIdFor(Specification::class)->nullable();
            $table->string('spec_type')->nullable();
            $table->boolean('is_key_feature')->nullable()->default(0);
            $table->timestamps();

            $table->index('vehicle_id');
            $table->index('specification_id');
            $table->index('spec_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_specs');
    }
};
