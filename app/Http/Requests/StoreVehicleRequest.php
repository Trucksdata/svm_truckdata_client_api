<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
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
            'title' => 'required|string|unique:vehicles,title',
            'manufacturer_id' => 'exists:manufacturers,id',
            'energy_source_id' => 'exists:energy_sources,id',
            'vehicle_type_id' => 'exists:vehicle_types,id',
            'series_id' => 'nullable|exists:series,id',
            'min_price' => 'required',
            'max_price' => 'nullable',
            'price_unit' => 'nullable',
            'images' => 'nullable',
            'is_popular' => 'boolean',
            'is_latest' => 'boolean',
            'is_upcoming' => 'boolean',
            'description' => 'nullable|string',
            'video_links' => 'nullable',
            'brochure' => 'nullable',
            'compare_vehicle_id' => 'nullable',
            'is_visible' => 'boolean',
            'category_name' => 'string|nullable',
            'faq' => 'nullable',
            //create  vehiclespec and options
            'vehicle_specs' => 'nullable|array',
            'vehicle_specs.*.specification_id' => 'required|exists:specifications,id',
            'vehicle_specs.*.spec_type' => 'required',
            'vehicle_specs.*.is_key_feature' => 'required|boolean',
            'vehicle_specs.*.values' => 'array',
            'vehicle_specs.*.values.*.value' => 'required',
            'vehicle_specs.*.values.*.child_values' => 'nullable|array',
            'vehicle_specs.*.values.*.child_values.*.value' => 'required_with:vehicle_specs.*.values.*.child_values'
        ];
    }
}
