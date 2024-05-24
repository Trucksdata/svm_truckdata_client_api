<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecificationRequest extends FormRequest
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
            'name' => 'required|unique:specifications,name',
            'specification_category_id' => 'required|exists:specification_categories,id',
            'data_type' => 'required|in:text,drop_down,nested_drop_down',
            'is_key_feature' => 'nullable',
            'options' => 'array',
            'options.*.option' => 'required_with:options',
            'options.*.child_options' => 'required_if:data_type,nested_drop_down',
            'options.*.child_options.*.option' => 'required_if:data_type,nested_drop_down',
        ];
    }
}
