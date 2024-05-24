<?php

use App\Models\EnergySource;
use App\Models\Specification;
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
        Schema::create('vehicle_type_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VehicleType::class);
            $table->foreignIdFor(EnergySource::class);
            $table->foreignIdFor(Specification::class);

            $table->index('vehicle_type_id');
            $table->index('energy_source_id');
            $table->index('specification_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_type_specifications');
    }
};
