<?php

namespace App\Filters;

use App\Filters\QueryFilter\QueryFilter;

class VehicleFilter extends QueryFilter
{
    public function title($title = null)
    {
        return $this->builder->where('title', 'like', '%' . $title . '%');
    }

    public function price($value = null)
    {
        $values = explode(',', $value);
        $start_price = $values[0] ?? null;
        $end_price = $values[1] ?? null;

        return $this->builder->whereBetween('min_price', [$start_price, $end_price]);
    }
    public function manufacturer($id = null)
    {
        return $this->builder->whereHas('manufacturer', function ($manufacturer) use ($id) {
            $manufacturer->where('id', $id);
        });
    }

    public function energy_source($id = null)
    {
        return $this->builder->whereHas('energySource', function ($energySource) use ($id) {
            $energySource->where('id', $id);
        });
    }

    public function vehicle_type($id = null)
    {
        return $this->builder->whereHas('vehicleType', function ($vehicleType) use ($id) {
            $vehicleType->where('id', $id);
        });
    }

    public function doesnt_have_compare($boolean = null)
    {
        return $this->builder->whereNull('compare_vehicle_id')->wheredoesntHave('compareVehicle');
    }

    public function application($value = null)
    {
        // Split the incoming value by commas
        $values = explode(',', $value);

        // Get the filtered specification ID and value from the request
        $filteredValues = $this->filterValues($values[0]);
        $spec_id = $filteredValues['spec_id'];
        $value = $values[1] ?? null;
        $sub_application = $values[2] ?? null;

        // Apply the filter to the query builder
        return $this->builder->whereHas('vehicleSpecs.allValues', function ($query) use ($spec_id, $value, $sub_application) {
            $query->where('specification_id', $spec_id)
                ->where(function ($subQuery) use ($value, $sub_application) {
                    if($value != null){
                        $subQuery->where('parent_option_id', $value);
                    }
                    if ($sub_application !== null) {
                        $subQuery->Where('value', $sub_application);
                    }
                });
        });
    }



    // public function subApplication($value = null)
    // {
    //     $filteredValues = $this->filterValues($value);
    //     $spec_id = $filteredValues['spec_id'];
    //     $value = $filteredValues['value'];

    //     return $this->builder->whereHas('vehicleSpecs.allValues.child_values', function ($values) use ($spec_id, $value,) {
    //         $values->where('specification_id', $spec_id);
    //         $values->where('value', $value);
    //     });
    // }

    public function gvw($value = null)
    {
        $values = explode(',', $value);
        $spec_id = $values[0] ?? null;
        $from_value = $values[1] ?? null;
        $to_value = $values[2] ?? null;

        // $filteredValues = $this->filterValues($value);
        // $spec_id = $filteredValues['spec_id'];
        // $value = $filteredValues['value'];

        // return $this->builder->whereHas('vehicleSpecs.allValues', function ($values) use ($spec_id, $value) {
        //     $values->where('specification_id', $spec_id);
        //     $values->where('value', $value);
        // });

        return $this->builder->whereHas('vehicleSpecs.allValues', function ($values) use ($spec_id, $from_value, $to_value) {
            $values->where('specification_id', $spec_id);
            if ($from_value !== null) {
                $values->where('value', '>=', $from_value * 1000);
            }
            if ($to_value !== null) {
                $values->where('value', '<=', $to_value * 1000);
            }
        });
    }


    public function loading_capacity($value = null)
    {
        $filteredValues = $this->filterValues($value);
        $spec_id = $filteredValues['spec_id'];
        $parent_value = (int)$filteredValues['parent_value'];
        $child_value = (int)$filteredValues['child_value'];

        return $this->builder->whereHas('vehicleSpecs.allValues', function ($values) use ($spec_id, $parent_value, $child_value) {
            $values->where('specification_id', $spec_id);
            $values->whereBetween('value', [$parent_value, $child_value]);
        });
    }
    public function axle_configuration($value = null)
    {
        $specIdAndValues = $this->filterWithMultipleValues($value);
        $spec_id = $specIdAndValues['spec_id'];
        $options = $specIdAndValues['options'];

        return $this->builder->whereHas('vehicleSpecs.allValues', function ($values) use ($spec_id, $options) {
            $values->where('specification_id', $spec_id);
            $values->whereIn('value', $options);
        });
    }

    public function payload_range($value = null)
    {
        $filteredValues = $this->filterValues($value);
        $spec_id = $filteredValues['spec_id'];
        $parent_value = (int)$filteredValues['parent_value'];
        $child_value = (int)$filteredValues['child_value'];

        return $this->builder->whereHas('vehicleSpecs.allValues', function ($values) use ($spec_id, $parent_value, $child_value) {
            $values->where('specification_id', $spec_id);
            $values->whereBetween('value', [$parent_value, $child_value]);
        });
    }
    public function chassis_options($value = null)
    {
        $specIdAndValues = $this->filterWithMultipleValues($value);
        $spec_id = $specIdAndValues['spec_id'];
        $options = $specIdAndValues['options'];

        return $this->builder->whereHas('vehicleSpecs.allValues', function ($values) use ($spec_id, $options) {
            $values->where('specification_id', $spec_id);
            $values->whereIn('value', $options);
        });
    }

    public function status($value = null)
    {
        $specIdAndValues = $this->filterWithMultipleValues($value);
        $spec_id = $specIdAndValues['spec_id'];
        $options = $specIdAndValues['options'];

        return $this->builder->whereHas('vehicleSpecs.allValues', function ($values) use ($spec_id, $options) {
            $values->where('specification_id', $spec_id);
            $values->whereIn('value', $options);
        });
    }

    public function variant_options($value = null)
    {
        $specIdAndValues = $this->filterWithMultipleValues($value);
        $spec_id = $specIdAndValues['spec_id'];
        $options = $specIdAndValues['options'];

        return $this->builder->whereHas('vehicleSpecs.allValues', function ($values) use ($spec_id, $options) {
            $values->where('specification_id', $spec_id);
            $values->whereIn('value', $options);
        });
    }

    public function number_of_tyres($value = null)
    {
        $specIdAndValues = $this->filterWithMultipleValues($value);
        $spec_id = $specIdAndValues['spec_id'];
        $options = $specIdAndValues['options'];

        return $this->builder->whereHas('vehicleSpecs.allValues', function ($values) use ($spec_id, $options) {
            $values->where('specification_id', $spec_id);
            $values->whereIn('value', $options);
        });
    }

    private function filterValues($value)
    {
        $values = explode(',', $value);
        $spec_id = $values[0] ?? null;
        $parent_value = $values[1] ?? null;
        $child_value = $values[2] ?? null;
        $value = $child_value ?: $parent_value;
        return [
            'spec_id' => $spec_id, 'value' => $value,
            'child_value' => $child_value, 'parent_value' => $parent_value
        ];
    }

    private function filterWithMultipleValues($queryParameter)
    {
        $arrayOfParameters = explode(',', $queryParameter);
        $spec_id = $arrayOfParameters[0] ?? null;
        unset($arrayOfParameters[0]);  //removing spec_id from array to make the "arrayOfParameters" full of options/values
        return ['spec_id' => $spec_id, 'options' => $arrayOfParameters];
    }
}
