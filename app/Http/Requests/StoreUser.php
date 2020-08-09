<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
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
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:App\Models\User,email'],
            'password' => [Rule::requiredIf($this->isMethod('post')), 'min:8'],
            'password_repeat' => [Rule::requiredIf($this->isMethod('post')), 'min:8', 'same:password'],
            'photo' => ['image']
        ];
        if ($this->isMethod('put')) {
            $addonRules = [
                'email' => ['required', 'email', Rule::unique('users')->ignore($this->user)],
            ];
            $rules = array_merge($rules, $addonRules);
        }
        return $rules;
    }
}
