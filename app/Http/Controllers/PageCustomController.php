<?php
namespace App\Http\Controllers;

use App\Helpers\ResizeHelper;
use App\Repository\PageRepositoryInterface;
use Illuminate\Support\Facades\Crypt;


class PageCustomController extends Controller
{

    public function _FuncionCustom($page,$title){
        var_dump('funcion custom');

        $title = $title . ' TITLE CUSTOM MODIFICADO';

        return view('page.view', compact('page', 'title'));
    }


}
