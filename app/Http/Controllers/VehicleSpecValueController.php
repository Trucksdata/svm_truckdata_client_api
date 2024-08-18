<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleSpecValueRequest;
use App\Http\Requests\UpdateVehicleSpecValueRequest;
use App\Models\VehicleSpec;
use App\Models\VehicleSpecValue;
use Illuminate\Http\Request;

class VehicleSpecValueController extends Controller
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
    public function index(Request $request, VehicleSpec $vehicleSpec)
    {
        $limit = $request->limit ?: 100;
        return VehicleSpecValue::where('vehicle_spec_id', $vehicleSpec->id)
            ->whereNull('parent_value_id')
            ->with('childValues')
            ->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVehicleSpecValueRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVehicleSpecValueRequest $request, VehicleSpec $vehicleSpec)
    {
        // Create the value with the validated data
        $value = $vehicleSpec->values()->create(array_merge(
            $request->validated(),
            [
            'parent_option_id' => $request->input('parent_value_id'), // Set parent_option_id from parent_value_id in request
            'parent_value_id' => null // Set parent_value_id to null
            ]
        ));

        // Return a success response with the created value
        return $this->successResponse('created', ['data' => $value]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VehicleSpecValue  $vehicleSpecValue
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleSpec $vehicleSpec, VehicleSpecValue $value)
    {
        return $value->load(['vehicleSpec', 'specification']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVehicleSpecValueRequest  $request
     * @param  \App\Models\VehicleSpecValue  $value
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVehicleSpecValueRequest $request, VehicleSpec $vehicleSpec, VehicleSpecValue $value)
    {
        $value->update($request->validated());
        return $this->successResponse('updated', ['data' => $value]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VehicleSpecValue  $vehicleSpecValue
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleSpec $vehicleSpec, VehicleSpecValue $value)
    {
        $value->delete();
        return $this->successResponse('deleted');
    }
}
