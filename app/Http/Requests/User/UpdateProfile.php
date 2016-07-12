<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class UpdateProfile extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullname' => ['required'],
            'gender'   => ['required', 'in:male,female'],
            'country'  => ['required', 'alpha_num'],
            'dob'      => ['date_format:Y-m-d'],
            'blogurl'  => ['url', 'min:3'],
            'fb_link'  => ['url', 'min:3'],
            'tw_link'  => ['url', 'min:3'],
            'country'  => ['country']
        ];
    }
}
