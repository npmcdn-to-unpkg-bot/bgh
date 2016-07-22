<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfAuthenticated
{

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }


    public function handle($request, Closure $next)
    {
        // REB saco este control porque un admin tiene que poder ver las cosas de un usuario
        // if ($this->auth->check()) {
        //     return redirect(route('home'));
        // }

        return $next($request);
    }
}
