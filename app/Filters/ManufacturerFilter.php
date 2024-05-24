<?php

namespace App\Filters;

use App\Filters\QueryFilter\QueryFilter;

class ManufacturerFilter extends QueryFilter
{
    public function name($name = null)
    {
        return $this->builder->where('name', 'like', '%' . $name . '%');
    }

    public function vehicle_type($type_id = null)
    {
        return $this->builder->whereHas('vehicleTypes', function ($vehicleType) use ($type_id) {
            $vehicleType->where('vehicle_type_id', $type_id);
        });
    }
}
