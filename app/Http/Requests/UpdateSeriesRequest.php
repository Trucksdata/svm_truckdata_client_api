<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeriesRequest extends FormRequest
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
            'title' => 'string|max:255|required',
            'description' => 'nullable|string',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
        ];
    }
}
