<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategory extends BaseFormRequest
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
            'name' => ['required', 'unique:categories,name'],
            'slug' => ['unique:categories,slug']
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'name' => ['required', Rule::unique('categories')->ignore($this->category)],
                'slug' => [Rule::unique('categories')->ignore($this->category)]
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}
