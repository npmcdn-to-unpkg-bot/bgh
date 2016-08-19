<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\JsonResponse;

use Carbon\Carbon;

class TestController extends Controller
{

    public function getIndex(Request $request)
    {

        $title = 'Test';

        return view('admin.test.index', compact('title'));
    }

}
