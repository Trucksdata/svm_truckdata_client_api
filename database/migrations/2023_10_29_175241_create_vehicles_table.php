<?php

use App\Models\EnergySource;
use App\Models\Manufacturer;
use App\Models\Series;
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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->foreignIdFor(Manufacturer::class)->nullable();
            $table->foreignIdFor(EnergySource::class)->nullable();
            $table->foreignIdFor(VehicleType::class)->nullable();
            $table->foreignIdFor(Series::class)->nullable();
            $table->decimal('min_price',10,2)->nullable();  
            $table->decimal('max_price',10,2)->nullable();
            $table->string('price_unit')->nullable();
            $table->json('images')->nullable();
            $table->json('video_links')->nullable();
            $table->json('brochure')->nullable();
            $table->boolean('is_popular')->default(0)->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();

            $table->index('manufacturer_id');
            $table->index('energy_source_id');
            $table->index('vehicle_type_id');
            $table->index('series_id');
            $table->index('is_popular');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
