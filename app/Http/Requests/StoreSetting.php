<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSetting extends BaseFormRequest
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
            'name' => ['required', 'unique:settings,name'],
            'value' => ['required']
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'name' => ['required', Rule::unique('settings')->ignore($this->setting)]
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}
