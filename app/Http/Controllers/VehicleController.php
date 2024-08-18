<?php

namespace App\Http\Controllers;

use App\Filters\VehicleFilter;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\CommonFaq;
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
            //'vehicleSpecs' => ['specification', 'values'],
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
        // Load the vehicle relationships
        $vehicle->load([
            'vehicleSpecs' => ['specification', 'values'],
            'vehicleType',
            'energySource',
            'manufacturer', // Load manufacturer relationship
            'parentVehicle'
        ]);
    
        // Fetch all FAQs
        $faqs = CommonFaq::all();
    
        // Define the replacements
        $replacements = [
            '{Variant Name}' => $vehicle->title,
            '{Min Price}' => $vehicle->min_price,
            '{Max Price}' => $vehicle->max_price,
        ];
    
        // Define the specifications to check
        $specificationsToCheck = [
            "Status",
            "Loading Span (ft) / Loading Capacity (Cu.M)",
            "Wheel Base (mm)",
            "Gross Vehicle Weight (Kg)",
            "Payload (Range)(Kg)",
            "Maximum Geared Speed (Kmph)",
            "Tyre Size",
            "Engine Model",
            "No of Cylinders",
            "Displacement (cc)",
            "Maximum Power"
        ];

        $highestValueCheckItems = ["Gross Vehicle Weight (Kg)","Payload (Range)(Kg)"];

        $avoidRepeats = ["Loading Span (ft) / Loading Capacity (Cu.M)","Wheel Base (mm)"];
    

        foreach ($vehicle->vehicleSpecs as $spec) {
            $specName = $spec->specification->name;
            // Check if the specification name is in the list of items to check
            if (in_array($specName, $specificationsToCheck)) {
                $specValues = $spec->values ?? null;
                $specValuesList = [];
        
                if ($specValues) {
                    foreach ($specValues as $specValue) {
                        $specValuesList[] = $specValue->value;
                    }
                }
                if ($specValuesList) {
                    $placeholder = '{' . $specName . '}';
                    $displayValue = null;

                    if (in_array($specName, $highestValueCheckItems)) {
                        if (!empty($specValuesList)) {
                            $displayValue = max($specValuesList);
                        }
                    }
                    else if(in_array($specName, $avoidRepeats)) {
                        if (!empty($specValuesList)) {
                            $displayValue = implode(', ',array_unique($specValuesList));
                        }
                    }
                    else{
                        $displayValue = implode(', ',$specValuesList);
                    }
                    $replacements[$placeholder] = $displayValue;
                }
            }
        }

        // Ensure all specifications are in $replacements
        foreach ($specificationsToCheck as $specification) {
            $placeholder = '{' . $specification . '}';

            // Check if the placeholder exists in replacements
            if (!array_key_exists($placeholder, $replacements)) {
                // Add the placeholder with value "-"
                $replacements[$placeholder] = "-";
            }
        }
        
        // Replace placeholders in FAQs
        foreach ($faqs as $faq) {
            foreach ($replacements as $placeholder => $value) {
                $faq->question = str_replace($placeholder, $value, $faq->question);
                $faq->answer = str_replace($placeholder, $value, $faq->answer);
            }
        }
    
        // Append the modified FAQs to the existing `faq` attribute
        $existingFaqs = $vehicle->faq ?? [];
        $vehicle->faqs = array_merge($existingFaqs, $faqs->toArray());
    
        //$vehicle->price_unit = $replacements;
        // Return the vehicle with the FAQs included
        return response()->json($vehicle);
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
