<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleSpecRequest;
use App\Http\Requests\UpdateVehicleSpecRequest;
use App\Models\Vehicle;
use App\Models\VehicleSpec;
use Illuminate\Http\Request;

class VehicleSpecController extends Controller
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
    public function index(Request $request, Vehicle $vehicle)
    {
        $limit = $request->limit ?: 100;
        return VehicleSpec::where('vehicle_id', $vehicle->id)->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVehicleSpecRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVehicleSpecRequest $request, Vehicle $vehicle)
    {
        $data = $request->validated();
        $vehicleSpec = $vehicle->vehicleSpecs()->create($data);
        $this->createVehicleSpecValues($vehicleSpec, $request->values);
        return $this->successResponse('created', ['data' => $vehicle->load('vehicleSpecs')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VehicleSpec  $vehicleSpec
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle, VehicleSpec $vehicleSpec)
    {
        return $vehicleSpec->load(['vehicle','values']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVehicleSpecRequest  $request
     * @param  \App\Models\VehicleSpec  $vehicleSpec
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVehicleSpecRequest $request, Vehicle $vehicle, VehicleSpec $vehicleSpec)
    {
        $vehicleSpec->update($request->validated());
        return $this->successResponse('updated', ['data' => $vehicleSpec]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VehicleSpec  $vehicleSpec
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle, VehicleSpec $vehicleSpec)
    {
        $vehicleSpec->delete();
        return $this->successResponse('deleted');
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
}
