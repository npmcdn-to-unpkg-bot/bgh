<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class Favorite extends Request
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
            'id' => ['required', 'integer']
        ];
    }
}
