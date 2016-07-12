<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class RegisterRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->check()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'              => ['required', 'min:3', 'max:20', 'alpha_num', 'Unique:users'],
            'fullname'              => ['required', 'min:3', 'max:80'],
            'gender'                => ['required', 'in:male,female'],
            'email'                 => ['required', 'between:3,64', 'email', 'unique:users'],
            'password'              => ['required', 'between:4,25', 'confirmed'],
            'password_confirmation' => ['required', 'between:4,25'],
            'g-recaptcha-response'  => ['required', 'recaptcha']
        ];
    }
}
