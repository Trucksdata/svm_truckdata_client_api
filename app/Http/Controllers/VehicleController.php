<?php

namespace App\Http\Controllers;

use App\Filters\VehicleFilter;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'super_admin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, VehicleFilter $filters)
    {
        $limit = $request->limit ?: 15;
        return Vehicle::with([
            'vehicleType:id,name',
            'manufacturer:id,name',
            'energySource',
            'payloadSpec'
        ])
            ->filter($filters)
            ->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVehicleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVehicleRequest $request)
    {
        $vehicleDataArray = $request->only([
            'min_price', 'max_price',
            'title', 'manufacturer_id', 'energy_source_id',
            'vehicle_type_id', 'series_id', 'images', 'is_popular',
            'description', 'video_links', 'brochure', 'is_latest',
            'is_upcoming', 'is_visible', 'category_name', 'faq'
        ]);

        $vehicle = Vehicle::create($vehicleDataArray);
        $vehicleSpecsDataArray = $request->vehicle_specs;

        foreach ($vehicleSpecsDataArray as $vehicleSpecDataArray) {
            $vehicleSpec = $this->createVehicleSpec($vehicle, $vehicleSpecDataArray);
            if (isset($vehicleSpecDataArray['values'])) {
                $this->createVehicleSpecValues($vehicleSpec, $vehicleSpecDataArray['values']);
            }
        }
        return $this->successResponse('created', ['data' => $vehicle->load('vehicleSpecs.values')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        return $vehicle->load(['vehicleSpecs' => ['specification', 'values'], 'vehicleType', 'energySource', 'manufacturer', 'parentVehicle']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVehicleRequest  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $data = $request->validated();

        if ($request->compare_vehicle_id != $vehicle->compare_vehicle_id) {
            $childVehicle = Vehicle::findOrFail($request->compare_vehicle_id);
            if ($this->checkCompareVehiclesAreNotMapped($vehicle, $childVehicle) == false) {
                return $this->errorResponse(['message' => 'Vehicles Already Mapped']);
            }
        }

        $vehicle->update($data);
        return $this->successResponse('updated', ['data' => $vehicle]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return $this->successResponse('deleted');
    }

    public function createVehicleSpec($vehicle, $vehicleSpec)
    {
        $specId = $vehicleSpec['specification_id'];
        $specType = $vehicleSpec['spec_type'];
        $isKeyFeature = $vehicleSpec['is_key_feature'];

        return $vehicleSpec = $vehicle->vehicleSpecs()->create([
            'specification_id' => $specId,
            'spec_type' => $specType,
            'is_key_feature' => $isKeyFeature
        ]);
    }

    public function createVehicleSpecValues($vehicleSpec, $vehicleSpecValues, $parentValueId = null)
    {
        foreach ($vehicleSpecValues as $vehicleSpecValue) {
            $value = $vehicleSpecValue['value'];
            $parentValue = $vehicleSpec->values()->create([
                'specification_id' => $vehicleSpec->specification_id,
                'value' => $value,
                'parent_value_id' => $parentValueId
            ]);
            if (isset($vehicleSpecValue['child_values'])) {
                $childValues = $vehicleSpecValue['child_values'];
                $this->createVehicleSpecValues($vehicleSpec, $childValues, $parentValue->id);
            }
        }
        return true;
    }

    private function checkCompareVehiclesAreNotMapped($parentVehicle, $childVehicle)
    {
        if ($parentVehicle->compare_vehicle_id !== $childVehicle->id && $childVehicle->parent_vehicle_id !== $parentVehicle->id) {
            // Ensure that the two vehicles are not already in a parent-child relationship
            return true;
        } elseif ($parentVehicle->id == $childVehicle->id) {
            return false;
        }
        return false;
    }
}
