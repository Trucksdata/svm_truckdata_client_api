<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecificationOptionRequest extends FormRequest
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
            'options' => 'array|required',
            'options.*.option' => 'required',
            'options.*.parent_option_id' => 'nullable',
            'options.*.child_options' => 'nullable',
            'options.*.child_options.*.option' => 'required_with:options.*.child_options',
        ];
    }
}
