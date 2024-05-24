<?php

namespace App\Imports;

use App\Models\EnergySource;
use App\Models\Manufacturer;
use App\Models\Series;
use App\Models\Specification;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VehicleImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public $manufacturers, $series, $vehicle_types, $energy_sources, $specifications;

    public function __construct()
    {
        $this->manufacturers = Manufacturer::get();
        $this->series = Series::get();
        $this->vehicle_types = VehicleType::get();
        $this->energy_sources = EnergySource::get();
        $this->specifications = Specification::get();
    }

    public function model(array $row)
    {
        $vehicle =  Vehicle::create([
            'title' => $row['title'],
            'manufacturer_id' => $this->manufacturers->toQuery()->where('name', 'like', '%' . $row['manufacturer'] . '%')->first()->id,
            'energy_source_id' => $this->energy_sources->toQuery()->where('name', 'like', '%' . $row['energy_source'] . '%')->first()->id,
            'vehicle_type_id' => $this->vehicle_types->toQuery()->where('name', 'like', '%' . $row['vehicle_type'] . '%')->first()->id,
            'series_id' => $this->series->toQuery()->where('title', 'like', '%' . $row['series'] . '%')->first()->id ?? null,
            'min_price' => $row['min_price'],
            'max_price' => $row['max_price'],
            // 'price_unit' => $row['price_unit'],
            'is_popular' => $row['is_popular'],
            'is_upcoming' => $row['is_upcoming'],
            'is_latest' => $row['is_latest'],
            'description' => $row['description'],
        ]);
        foreach ($row as $heading => $values) {
            Log::info($heading);
            $this->checkRowContainsSpecification($heading, $values, $vehicle);
        }
        return $vehicle;
    }

    public function checkRowContainsSpecification($heading, $values, $vehicle)
    {
        // Using preg_match
        if (preg_match('/spec_(.*)/', $heading, $matches)) {
            $specification = $matches[1];
            $specificationSlug = str_replace('_', '-', $specification);
            $specValues = explode(',', $values);
            $this->createVehicleSpecificationAndValues($vehicle, $specificationSlug, $specValues);
        } else {
            return false;
        }
    }
    public function createVehicleSpecificationAndValues($vehicle, $specificationSlug, $specValues)
    {
        $specification = $this->specifications->toQuery()->where('slug',$specificationSlug)->first() ?? null;
        Log::info([$specification,$vehicle]);
        if ($specification) {
            $vehicleSpec = $vehicle->vehicleSpecs()->create([
                'specification_id' => $specification->id,
                'spec_type' => $specification->data_type,
            ]);
            Log::info($vehicleSpec);
            foreach ($specValues as $specValue) {
                $parentValue = $vehicleSpec->values()->create([
                    'specification_id' => $vehicleSpec->specification_id,
                    'value' => $specValue
                ]);
            }
        }
    }
}
