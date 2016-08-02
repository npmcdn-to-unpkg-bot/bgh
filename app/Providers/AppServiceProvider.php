<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

// use App\Http\Requests\Request; REB no es abstracta
use Illuminate\Http\Request;


class AppServiceProvider extends ServiceProvider
{

    public function boot(Request $request)
    {

        view()->share('testinallviews', 'prueba de dato en todas las vistas');

        // reb determino la url host para saber que base de datos levanto (en test es con ?country=uy)
        $host = $request->getHost();
        // var_dump($host);
        if ($request->input('country') == 'uy') {
            \Config::set('database.default', 'mysql_uy');
        }



        // reb cambio el idioma en base a la configuracion de la tabla settings
        \Config::set('app.locale', siteSettings('locale'));
        app()->setLocale(\Config::get('app.locale'));
        setlocale(LC_TIME, \Config::get('app.locale'));
        // \Carbon\Carbon::setLocale(\Config::get('app.locale'));
        // https://github.com/rappasoft/laravel-5-boilerplate/issues/211

        // reb cambio el UTM en base a la configuracion de la tabla settings
        \Config::set('app.timezone', siteSettings('timezone'));
        date_default_timezone_set(\Config::get('app.timezone'));

        Validator::extend('country', function ($attribute, $value, $parameters) {
            return countryIsoCodeMatch($value) == true;
        });

    }

    public function register()
    {

    }

}
