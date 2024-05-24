<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleTypeSpecificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'specifications' => 'array|required',
            'specifications.*.specification_id' => 'required|exists:specifications,id',
            'specifications.*.energy_source_id' => 'required|exists:energy_sources,id'
        ];
    }
}
