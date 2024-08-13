<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleTypeRequest;
use App\Http\Requests\UpdateVehicleTypeRequest;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
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
        $limit = $request->limit ?: 100;
        
        return VehicleType::with([
            'manufacturers.series.vehicles' => function ($query) {
                $query->where('is_visible', 1)
                    ->orderBy('title', 'asc'); // Assuming the vehicles have a 'name' column
            }, 
            'energySources'
        ])->paginate($limit);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVehicleTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVehicleTypeRequest $request)
    {
        $data = VehicleType::create($request->validated());
        $data->manufacturers()->attach($request->manufacturers);
        $data->energySources()->attach($request->energy_sources);

        return $this->successResponse('created', ['data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VehicleType  $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleType $vehicleType)
    {
        return $vehicleType->load([
            'manufacturers.series' => function ($series) use ($vehicleType) {
                $series->where('vehicle_type_id', $vehicleType->id);
            },
            'energySources.specifications' => function ($specifications) use ($vehicleType) {
                $specifications->where('vehicle_type_id', $vehicleType->id);
            }
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVehicleTypeRequest  $request
     * @param  \App\Models\VehicleType  $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicleType)
    {
        $vehicleType->update($request->validated());
        $vehicleType->manufacturers()->sync($request->manufacturers);
        $vehicleType->energySources()->sync($request->energy_sources);

        return $this->successResponse('updated', ['data' => $vehicleType]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VehicleType  $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleType $vehicleType)
    {
        $vehicleType->delete();
        return $this->successResponse('deleted');
    }

    public function attachManufacturers(Request $request, VehicleType $vehicleType)
    {
        $request->validate([
            'manufacturers' => 'array|required',
        ]);
        $vehicleType->manufacturers()->attach($request->manufacturers);
        return $this->successResponse('attached', ['data' => $vehicleType]);
    }

    public function detach(Request $request, VehicleType $vehicleType)
    {
        $request->validate([
            'manufacturers' => 'array|required',
        ]);
        $vehicleType->manufacturers()->detach($request->manufacturers);
        return $this->successResponse('attached', ['data' => $vehicleType]);
    }
}
