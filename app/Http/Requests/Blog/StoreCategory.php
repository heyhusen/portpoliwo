<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategory extends FormRequest
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
            'title' => ['required', 'unique:blog_categories,title'],
            'slug' => ['unique:blog_categories,slug']
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'title' => ['required', Rule::unique('blog_categories')->ignore($this->category)],
                'slug' => [Rule::unique('blog_categories')->ignore($this->category)]
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}
