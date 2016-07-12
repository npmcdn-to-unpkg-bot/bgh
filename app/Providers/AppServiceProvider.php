<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

// use App\Http\Requests\Request; REB no es abstracta
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {

        $host = $request->getHost();

        if ($request->input('country') == 'uy') {

            \Config::set('database.default', 'mysql_uy');

            \Config::set('app.locale', env('LOCALE_UY','en'));
            app()->setLocale(env('LOCALE_UY',\Config::get('app.locale')));

            \Config::set('app.timezone', env('TIMEZONE_UY','UTC'));
            date_default_timezone_set(\Config::get('app.timezone'));

        }

        // var_dump($host);

        Validator::extend('country', function ($attribute, $value, $parameters) {
            return countryIsoCodeMatch($value) == true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
