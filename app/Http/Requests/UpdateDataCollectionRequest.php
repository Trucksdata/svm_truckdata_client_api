<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDataCollectionRequest extends FormRequest
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
            'name'     => 'nullable|string|max:255',
            'email'    => 'nullable|email|max:255',
            'district' => 'nullable|string|max:255',
            'state'    => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
        ];
    }
}
