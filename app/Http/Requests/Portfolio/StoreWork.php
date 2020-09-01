<?php

namespace App\Http\Requests\Portfolio;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWork extends FormRequest
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
            'name' => ['required', 'unique:works,name'],
            'description' => ['required'],
            'url' => ['url'],
            'photo' => [Rule::requiredIf($this->isMethod('post')), 'image'],
            'category_id' => [Rule::requiredIf($this->isMethod('post')), 'array', 'min:1'],
            'category_id.*' => ['required', 'min:1'],
            'tag_id' => [Rule::requiredIf($this->isMethod('post')), 'array', 'min:1'],
            'tag_id.*' => ['required', 'min:1'],
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'name' => ['required', Rule::unique('works')->ignore($this->work)],
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'category_id' => 'category',
            'tag_id' => 'tag'
        ];
    }
}
