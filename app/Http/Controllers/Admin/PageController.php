<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Resize;
use App\Helpers\ResizeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

use App\Models\Page;
use App\Http\Requests\Admin\PageRequest;
use App\Repository\PageRepositoryInterface;


class PageController extends Controller
{

    public function getIndex(Request $request)
    {

        $title = sprintf('List of %s pages', ucfirst($request->get('type')));
        $type = $request->get('type');

        return view('admin.page.index', compact('title', 'type'));
    }

    public function getData(Request $request)
    {
        $pages = Page::select([
            'pages.id',
            'pages.slug',
            'pages.title',
            'pages.created_at',
            'pages.updated_at',
            'pages.id',
            'users.fullname as user_fullname',
            'profiles.title as profile_name'
        ])
        ->leftJoin('users', 'users.id', '=', 'pages.user_id')
        ->leftJoin('profiles', 'profiles.id', '=', 'pages.profile_id');


        $datatables = app('datatables')->of($pages);

        $datatables->addColumn('actions', function ($page) {
            return '
                    <div class="btn-group pull-right btn-group-sm" role="group" aria-label="Actions">
                        <a href="' . route('page', [$page->slug]) . '" class="btn btn-sm btn-default" target="_blank"><i class="fa fa-eye"></i> View </a>
                        <a href="' . route('admin.pages.edit', [$page->id]) . '" class="btn btn-sm btn-default"><i class="fa fa-edit"></i> Edit </a>
                        <a href="' . route('admin.pages.clone', [$page->id]) . '" class="btn btn-sm btn-default"><i class="fa fa-clone"></i> Clone </a>
                    </div>';
        });

        return $datatables->editColumn('created_at', '{!! $created_at->diffForHumans() !!}')
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->editColumn('title', '{!! str_limit($title, 60) !!}')
            ->addColumn('user', '{!! $user_fullname !!}')
            ->addColumn('profile', '{!! $profile_name !!}')
            ->make(true);

    }


    public function editor($id)
    {
        $page = Page::whereId($id)->with('user')->firstOrFail();

        $title = t('Edit');

        return view('admin.page.editor', compact('page', 'title'));
    }


    // #################################
    // REB metodos que responden al routes en modo REST con verbs (PUT, PATCH, DELETE) para no usar el post en distitnas rutas y ser mas organico
    // #################################

    public function edit($id)
    {
        $page = Page::whereId($id)->with('user')->firstOrFail();

        $title = t('Edit');


        $profiles = selectableProfiles()->lists('title','id');

        return view('admin.page.edit', compact('page', 'title', 'profiles'));
    }




    public function patch(PageRequest $request)
    {
        $item = Page::whereId($request->route('id'))->firstOrFail();

        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        } else {
            $tags = null;
        }
        $item->tags = $tags;

        $slug = @str_slug($request->get('slug'));
        if (!$slug) {
            $slug = str_random(8);
        }
        $item->slug = $slug;

        $item->title = $request->get('title');
        $item->html = $request->get('html');

        $item->profile()->associate($request->get('profile'));

        $item->save();

        if ($request->ajax() || $request->wantsJson()) {
            // return response()->json(['dato' => 'valor', 'otrodato' => 'otrovalor']);
            return new JsonResponse('ajax todo ok', 200);
        }
        else{
            return redirect()->back()->with('flashSuccess', 'post todo ok');
        }

    }


    public function create()
    {
        $title = 'Creating new page';

        return view('admin.page.create', compact('title'));
    }


    public function put(Request $request)
    {
        $item = new Page();
        $item->title = $request->get('title');

        $slug = @str_slug($request->get('title'));
        if (!$slug) {
            $slug = str_random(7);
        }
        $item->slug = $slug;

        $item->user_id = auth()->user()->id;

        $item->save();

        return redirect()->route('admin.pages.edit', ['id' => $item->id])->with('flashSuccess', 'Page is now crated');
    }


    public function delete($id)
    {

        // if (Request::ajax()) {
        // if (Request::isMethod('delete')){

        $page = Page::findOrFail($id);

        $page->delete();

        return redirect()->route('admin.pages')->with('flashSuccess', 'deleted');

    }

    public function doClone($id)
    {

        $source_page = Page::findOrFail($id);

        $page = $source_page->replicate();
        $page->push();

        $title = t('Edit the Clone');

        // return view('admin.page.edit', compact('page', 'title'));
        return redirect()->route('admin.pages.edit',['id' => $page->id])->with('flashSuccess', 'Clonado');
    }




}
