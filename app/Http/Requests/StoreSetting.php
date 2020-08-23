<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSetting extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            'site_name' => ['required'],
            'site_description' => ['required'],
            'company_name' => ['required'],
            'company_address' => ['required'],
            'company_phone_number' => ['required'],
            'company_email' => ['required', 'email'],
        ];
        return $rules;
    }
}
