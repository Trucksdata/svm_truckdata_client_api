<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomePageSettingRequest extends FormRequest
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
            'banners' => 'nullable',
            'heading' => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'logo' => 'nullable',
            'faq' => 'nullable',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'facebook_url' => 'nullable',
            'instagram_url' => 'nullable',
            'youtube_url' => 'nullable',
            'twitter' => 'nullable',
        ];
    }
}
