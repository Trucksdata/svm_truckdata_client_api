<?php

use App\Models\Specification;
use App\Models\VehicleSpec;
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
        Schema::create('vehicle_spec_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VehicleSpec::class);
            $table->foreignIdFor(Specification::class)->nullable();
            $table->string('value')->nullable();
            $table->unsignedBigInteger('parent_value_id')->nullable();
            $table->timestamps();

            $table->index('vehicle_spec_id');
            $table->index('specification_id');
            $table->index('parent_value_id');
            $table->index('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_spec_values');
    }
};
