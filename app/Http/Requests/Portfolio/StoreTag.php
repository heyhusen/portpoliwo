<?php

namespace App\Http\Requests\Portfolio;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTag extends FormRequest
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
            'name' => ['required', 'unique:tags,name'],
            'slug' => ['unique:tags,slug']
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'name' => ['required', Rule::unique('tags')->ignore($this->tag)],
                'slug' => [Rule::unique('tags')->ignore($this->tag)]
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}
