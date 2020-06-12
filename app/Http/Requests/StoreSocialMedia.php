<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSocialMedia extends FormRequest
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
            'name' => ['required', 'unique:social_medias,name'],
            'icon' => ['required'],
            'url' => ['required']
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'name' => ['required', Rule::unique('social_medias')->ignore($this->social_media)]
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}
