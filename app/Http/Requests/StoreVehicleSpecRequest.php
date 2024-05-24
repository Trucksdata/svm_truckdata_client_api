<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleSpecRequest extends FormRequest
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
            'specification_id' => 'required|exists:specifications,id',
            'spec_type' => 'required',
            'is_key_feature' => 'required',
            'values' => 'array',
            'values.*.value' => 'required',
            'values.*.child' => 'nullable|array',
            'values.*.child.*.value' => 'required_with:values.*.child'
        ];
    }
}
