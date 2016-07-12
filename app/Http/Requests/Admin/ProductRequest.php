<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Carbon\Carbon;

class ProductRequest extends Request
{


    public function authorize()
    {

        // $numberOfUploadByUser = auth()->user()->products()->where('created_at', '>=', Carbon::now()->subDays(1)->toDateTimeString())->count();
        // if ((int)$numberOfUploadByUser >= (int)limitPerDay()) {
        //     return false;
        // }

        return auth()->check();
    }


    public function rules()
    {
        return [
            // 'files'          => ['required', 'product', 'mimes:jpeg,jpg,bmp,png,gif', 'max:' . (int)siteSettings('maxProductSize') * 1000],
            'title'    => ['required'],
            'cover_image' => ['image','dimensions:min_width=200,min_height=200'],
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'y el titulo???',
            'cover_image.dimensions' => 'el tamaño de la foto de cover es invalido',
        ];
    }


}
