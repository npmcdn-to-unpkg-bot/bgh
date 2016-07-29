<?php

namespace App\Http\Requests\Admin;

// reb para el override del response, por tema ajax 422
use Illuminate\Http\JsonResponse;

use App\Http\Requests\Request;
use Carbon\Carbon;

class MediaRequest extends Request
{


    public function authorize()
    {

        // $numberOfUploadByUser = auth()->user()->media()->where('created_at', '>=', Carbon::now()->subDays(1)->toDateTimeString())->count();
        // if ((int)$numberOfUploadByUser >= (int)limitPerDay()) {
        //     return false;
        // }

        // return auth()->check();

        return true;
    }


    // reb si hago override de response para que salga por 200 en vez de 422, evito error en consola, pero no trapeo la validacion por el error: del ajax
    // public function response(array $errors)
    // {

    //     if (($this->ajax() && ! $this->pjax()) || $this->wantsJson()) {
    //         return new JsonResponse($errors, 200);
    //     }

    //     return $this->redirector->to($this->getRedirectUrl())
    //                                     ->withInput($this->except($this->dontFlash))
    //                                     ->withErrors($errors, $this->errorBag);

    // }


    public function rules()
    {
        return [
            // 'files'          => ['required', 'media', 'mimes:jpeg,jpg,bmp,png,gif', 'max:' . (int)siteSettings('maxMediaSize') * 1000],
            'title'    => ['required'],
            'cover_image' => ['image','dimensions:min_width=1000,min_height=200'],
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
