<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnergySourceRequest;
use App\Http\Requests\UpdateEnergySourceRequest;
use App\Models\EnergySource;
use Illuminate\Http\Request;

class EnergySourceController extends Controller
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
        return EnergySource::paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEnergySourceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEnergySourceRequest $request)
    {
        $data = EnergySource::firstOrCreate($request->validated());
        return $this->successResponse('create', ['data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EnergySource  $energySource
     * @return \Illuminate\Http\Response
     */
    public function show(EnergySource $energySource)
    {
        return $energySource;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEnergySourceRequest  $request
     * @param  \App\Models\EnergySource  $energySource
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEnergySourceRequest $request, EnergySource $energySource)
    {
        $energySource->update($request->validated());
        return $this->successResponse('updated', $energySource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EnergySource  $energySource
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnergySource $energySource)
    {
        $energySource->delete();
        return $this->successResponse('deleted');
    }
}
