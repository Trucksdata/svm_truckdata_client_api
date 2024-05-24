<?php

namespace App\Http\Controllers;

use App\Filters\VehicleFilter;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function getMappedVehicles(Request $request, VehicleFilter $filters)
    {
        $limit = $request->limit ?: 30;
        return Vehicle::has('compareVehicle')
            ->with('compareVehicle')
            ->filter($filters)
            ->paginate($limit);
    }
}
