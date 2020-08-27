<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePost extends FormRequest
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
            'title' => ['required', 'unique:blog_posts,title'],
            'slug' => ['unique:blog_posts,slug'],
            'content' => ['required'],
            'image' => [Rule::requiredIf($this->isMethod('post')), 'image'],
            'blog_category_id' => ['required', 'array', 'min:1'],
            'blog_category_id.*' => ['required', 'min:1'],
            'blog_tag_id' => ['required', 'array', 'min:1'],
            'blog_tag_id.*' => ['required', 'min:1'],
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'title' => ['required', Rule::unique('blog_posts')->ignore($this->post)],
                'slug' => [Rule::unique('blog_posts')->ignore($this->post)],
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
            'blog_category_id' => 'category',
            'blog_tag_id' => 'tag'
        ];
    }
}
