<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class SocialRegister extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->check() || $this->session()->has($this->route('provider') . '_register') == false) {
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
        if ($this->method() == 'GET') {
            return [];
        }

        return [
        ];

    }

    public function forbiddenResponse()
    {
        return redirect()->route('gallery')->with('flashError', t('You are not allowed'));
    }
}
