<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateManufacturerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'country_of_origin' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:0',
            'website' => 'nullable|url|max:255',
            'contact_info' => 'nullable|string',
            'description' => 'nullable|string',
            'logo' => 'nullable',
            'banners' => 'nullable',
            'vehicle_types' => 'array',
            'faq' => 'nullable'
        ];
    }
}
