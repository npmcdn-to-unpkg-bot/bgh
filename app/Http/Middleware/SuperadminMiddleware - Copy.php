<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class SuperadminMiddleware
{

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }


    public function handle($request, Closure $next)
    {

        if ($this->auth->check()) {

            if ($this->auth->user()->isSuper()) {
                return $next($request);
            }
            else{
                return redirect()->back()->with('flashError', 'User is not Superadmin');
            }

        }
        else{
            return redirect()->back()->with('flashError', 'No user authenticated');
        }

    }

}
