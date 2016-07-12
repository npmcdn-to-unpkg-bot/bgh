<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;

class LoginRequest extends Request
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
        if (Session::get('force_captcha')) {
            return [
                'username'             => ['required'],
                'password'             => ['required'],
                'g-recaptcha-response' => ['required', 'recaptcha']
            ];
        }

        return [
            'username' => ['required'],
            'password' => ['required']
        ];
    }
}
