<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{

    use ResetsPasswords;

    protected $redirectPath = 'home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getEmail()
    {
        $title = t('Password Reset');

        return view('auth.password', compact('title'));
    }

    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }
        $title = t('Password Reset');

        return view('auth.reset', compact('token', 'title'));
    }

}
