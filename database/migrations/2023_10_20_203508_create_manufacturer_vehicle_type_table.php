<?php

use App\Models\Manufacturer;
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
        Schema::create('manufacturer_vehicle_type', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VehicleType::class);
            $table->foreignIdFor(Manufacturer::class);

            $table->index(['vehicle_type_id', 'manufacturer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturer_vehicle_type');
    }
};
