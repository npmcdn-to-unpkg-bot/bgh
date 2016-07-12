<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class HomeController extends Controller {


    /**
     * @return mixed
     */
    public function getIndex()
    {
        $title = siteSettings('siteName');

        return view('home/index', compact('title'));
    }

}