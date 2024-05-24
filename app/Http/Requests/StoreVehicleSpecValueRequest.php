<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleSpecValueRequest extends FormRequest
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
            // 'vehicle_spec_id' => 'required|exists:vehicle_specs,id',
            'specification_id' => 'required|exists:specifications,id',
            'value' => 'required',
            'parent_value_id' => 'nullable'
        ];
    }
}
