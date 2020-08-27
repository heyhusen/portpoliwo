<?php

namespace App\Http\Requests\Blog;

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
            'title' => ['required', 'unique:blog_tags,title'],
            'slug' => ['unique:blog_tags,slug']
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'title' => ['required', Rule::unique('blog_tags')->ignore($this->tag)],
                'slug' => [Rule::unique('blog_tags')->ignore($this->tag)]
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}
