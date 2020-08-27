<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePage extends FormRequest
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
            'title' => ['required', 'unique:blog_pages,title'],
            'slug' => ['unique:blog_pages,slug'],
            'content' => ['required'],
            'image' => [Rule::requiredIf($this->isMethod('post')), 'image'],
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'title' => ['required', Rule::unique('blog_pages')->ignore($this->page)],
                'slug' => [Rule::unique('blog_pages')->ignore($this->page)],
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}
