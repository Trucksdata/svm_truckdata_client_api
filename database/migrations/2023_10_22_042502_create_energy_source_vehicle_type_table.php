<?php

use App\Models\EnergySource;
use App\Models\VehicleType;
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
        Schema::create('energy_source_vehicle_type', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EnergySource::class);
            $table->foreignIdFor(VehicleType::class);

            $table->index('energy_source_id');
            $table->index('vehicle_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('energy_source_vehicle_type');
    }
};
