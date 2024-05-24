<?php

namespace App\Http\Controllers;

use App\Imports\VehicleImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'file|required',
        ]);

        Excel::import(new VehicleImport, $request->file('file'));

        return $this->successResponse(['message' => ' import success']);
    }
}
