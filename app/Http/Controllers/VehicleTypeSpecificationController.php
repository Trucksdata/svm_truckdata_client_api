<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleTypeSpecificationRequest;
use App\Http\Requests\UpdateVehicleTypeSpecificationRequest;
use App\Models\VehicleType;
use App\Models\VehicleTypeSpecification;
use Illuminate\Http\Request;

class VehicleTypeSpecificationController extends Controller
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
    public function index(Request $request)
    {
        $request->validate([
            'vehicle_type_id' => 'exists:vehicle_types,id|required',
            'energy_source_id' => 'exists:energy_sources,id'
        ]);

        return  VehicleTypeSpecification::with(['specification', 'vehicleType', 'energySource'])
            ->where('vehicle_type_id', $request->vehicle_type_id)
            ->when($request->energy_source_id, function ($vehicleTypeSpecification) use ($request) {
                $vehicleTypeSpecification->where('energy_source_id', $request->energy_source_id);
            })
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVehicleTypeSpecificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVehicleTypeSpecificationRequest $request)
    {
        $vehicleType = VehicleType::findOrFail($request->vehicle_type_id);
        $vehicleType->specifications()->createMany($request->specifications);

        return $this->successResponse('created', ['data' => $vehicleType->specifications]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VehicleTypeSpecification  $vehicleTypeSpecification
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleTypeSpecification $vehicleTypeSpecification)
    {
        return $vehicleTypeSpecification->load(['specification', 'vehicleType', 'energySource']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVehicleTypeSpecificationRequest  $request
     * @param  \App\Models\VehicleTypeSpecification  $vehicleTypeSpecification
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVehicleTypeSpecificationRequest $request, VehicleTypeSpecification $vehicleTypeSpecification)
    {
        $vehicleTypeSpecification->update($request->validated());

        return $this->successResponse('updated', ['data' => $vehicleTypeSpecification]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VehicleTypeSpecification  $vehicleTypeSpecification
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleTypeSpecification $vehicleTypeSpecification)
    {
        $vehicleTypeSpecification->delete();
        return $this->successResponse('deleted');
    }
}
