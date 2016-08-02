<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AdminMiddleware
{

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }


    public function handle($request, Closure $next)
    {

        if ($this->auth->check()) {

            // if ($this->auth->user()->permission == 'admin') {
            if ($this->auth->user()->isAdmin() || $this->auth->user()->isSuper()) {
                return $next($request);
            }
            else{
                return redirect(route('home'))->with('flashError', 'User is not admin');
            }

        }
        else{
            return redirect(route('home'))->with('flashError', 'No user authenticated');
        }

        // return redirect(route('home'));
        // return redirect(route('home'))->with('flashError', 'Admins only');
        // return redirect()->back()->with('flashError', 'Admins only');
    }

}
