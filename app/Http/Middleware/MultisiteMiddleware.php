<?php

namespace App\Http\Middleware;

use Closure;

class MultisiteMiddleware
{

   public function handle($request, Closure $next)
    {

        // REB saqué el multilenguaje de acá porque lo puse en el AppServiceProvider

		// if ($request->input('country') == 'uy') {

  //       	// $request->attributes->add(['siteCode' => 'uy']);

		// 	\Config::set('database.default', 'mysql_uy');

	 //        \Config::set('app.locale', env('LOCALE_UY','en'));
	 //        app()->setLocale(env('LOCALE_UY',\Config::get('app.locale')));

  //           \Config::set('app.timezone', env('TIMEZONE_UY','UTC'));
  //           date_default_timezone_set(\Config::get('app.timezone'));

  //           // return redirect('home');
  //       }

        return $next($request);
    }

}
