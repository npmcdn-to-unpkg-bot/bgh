<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Resize;
use App\Helpers\ResizeHelper;
use App\Models\Profile;
use App\Models\Media;
use App\Models\MediaInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\JsonResponse;

use App\Repository\MediaRepositoryInterface;
use Carbon\Carbon;

use App\Http\Requests\Admin\MediaRequest;


class MediaController extends Controller
{

    public function getList(Request $request)
    {

        $title = t('List') . sprintf(': %s', ucfirst($request->get('type')));
        $type = $request->get('type');

        return view('admin.media.list', compact('title', 'type'));
    }

    public function getData(Request $request)
    {

        $medias = Media::select([
            'medias.*','users.fullname as user_fullname','profiles.title as profile_name'
        ])
        ->leftJoin('users', 'users.id', '=', 'medias.user_id')
        ->leftJoin('profiles', 'profiles.id', '=', 'medias.profile_id');


        // si no es superadmin, filtro el lote por los perfiles que el usuario posea
        if(!auth()->user()->isSuper()){
            $medias->whereIn('profile_id', auth()->user()->profiles()->lists('id')); // lo segundo es un array de ids
        }

        // switch ($request->get('type')) {
        //     case 'approved':
        //         $medias->approved(); // es del scopeApproved en el Model
        //         break;
        //     case 'approvalRequired':
        //         $medias->whereNull('medias.approved_at');
        //         break;
        //     default:
        //         $medias->approved();
        // }


        $datatables = app('datatables')->of($medias);

        $datatables->addColumn('actions', function ($media) {
            return '
            <div class="btn-group pull-right btn-group-sm" role="group" aria-label="Actions">
                <a href="' . route('admin.media.edit', [$media->id]) . '" class="btn btn-default"><i class="fa fa-edit"></i> Edit </a>
                <a href="' . route('media', [$media->id, $media->slug]) . '" class="btn btn-default" target="_blank"><i class="fa fa-eye"></i> View</a>
            </div>';
        });

        return $datatables->addColumn('thumbnail', function ($media) {
            return '<img src="' . Resize::img($media->main_image, 'listingMedia') . '" style="width:80px"/>';
        })
            ->editColumn('created_at', '{!! $created_at->diffForHumans() !!}')
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->addColumn('user', '{!! $user_fullname !!}')
            ->addColumn('profile', '{!! $profile_name !!}')
            ->make(true);
    }

    // #################################
    // REB metodos que responden al routes en modo REST con verbs (PUT, PATCH, DELETE) para no usar el post en distitnas rutas y ser mas organico
    // #################################

    public function edit($id)
    {
        $media = Media::whereId($id)->with('user', 'info')->firstOrFail();

        if(!$media->canHandle()){
            return redirect()->route('admin')->with('flashSuccess', 'sin acceso a editar este mediao');
        }

        $title = t('Edit');

        $profiles = selectableProfiles()->lists('title','id');

        return view('admin.media.edit', compact('media', 'title', 'profiles'));
    }


    public function patch(MediaRequest $request)
    {
        // $media = Media::whereId($request->route('id'))->firstOrFail();
        $item = Media::findOrFail($request->route('id'));

        if(!$item->canHandle()){
            return redirect()->route('admin')->with('flashSuccess', 'sin acceso a editar este mediao');
        }

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
        $item->description = $request->get('description');

        $item->profile()->associate($request->get('profile'));


        if ($request->hasFile('main_image')){
            if ($request->file('main_image')->isValid()){
                $save = new ResizeHelper($request->file('main_image'), 'uploads/medias');
                list($fName, $fType) = $save->saveOriginal();
                $item->main_image = $fName . "." . $fType;
                // $request->file('main_image')->move($destinationPath, $fileName);
            }
        }

        $item->save();


        if ($request->hasFile('cover_image')){
            if ($request->file('cover_image')->isValid()){
                $save = new ResizeHelper($request->file('cover_image'), 'uploads/medias');
                list($fName, $fType) = $save->saveOriginal();
                $item->info->cover_image = $fName . "." . $fType;
                // $request->file('cover_image')->move($destinationPath, $fileName);
            }
        }

        $item->info->save();


        if ($request->ajax() || $request->wantsJson()) {
            // return response()->json(['dato' => 'valor', 'otrodato' => 'otrovalor']);
            return new JsonResponse('ajax todo ok', 200);
        }
        else{
            return redirect()->back()->with('flashSuccess', 'post todo ok');
        }

    }


    public function put(Request $request)
    {
        $item = new Media();
        $item->title = $request->get('title');

        $slug = @str_slug($request->get('slug'));
        if (!$slug) {
            $slug = str_random(7);
        }
        $item->slug = $slug;

        $item->user_id = auth()->user()->id;

        $item->save();

        $info_data = [
            'cover_image' => '',
        ];

        $info = new MediaInfo($info_data);
        $item->info()->create($info_data);

        return redirect()->route('admin.media.edit', ['id' => $item->id])->with('flashSuccess', 'Media is now crated');
    }


    public function delete($id)
    {
        // if (Request::ajax()) {
        // if (Request::isMethod('delete')){

        $item = Media::findOrFail($id);

        if(!$item->canHandle()){
            return redirect()->route('admin')->with('flashSuccess', 'sin acceso a editar este mediao');
        }

        $delete = new ResizeHelper( $item->main_image, $item->type);
        $delete->delete();

        $item->categories()->detach();
        $item->info()->delete();
        $item->delete();

        return redirect()->route('admin.media')->with('flashSuccess', 'deleted');
    }


    public function approve(Request $request)
    {
        $item = Media::whereId($request->get('id'))->first();
        if (!$item) {
            return 'Error';
        }
        if ($request->get('approve') == 1) {
            $item->approved_at = Carbon::now();
            $item->save();

            return 'Approved';
        }
        if ($request->get('approve') == 0) {
            $delete = new ResizeHelper($item->main_image, $item->type);
            $delete->delete();
            $item->delete();

            return 'Deleted';
        }
    }


    public function clearCache($id)
    {

        $media = Media::whereId($id);

        if(!isset($media->main_image)){
           $media->main_image = 'default.png';
        }

        $cache = new ResizeHelper($media->main_image);
        $cache->clearCache();
        return 'Cache is cleared, reload the page';


    }

    public function getBulkUpload()
    {
        $title = sprintf('Bulkupload');

        return view('admin.media.bulkupload', compact('title'));
    }

    public function postBulkUpload(Request $request)
    {
        $file = $request->file('files')[0];
        $info = $request->get('photo');

        $save = new ResizeHelper($file, 'uploads/medias');
        list($mediaName, $mimetype) = $save->saveOriginal();

        $tags = null;
        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        }

        $description = null;

        $title = str_replace(['.jpg', '.jpeg', '.png', '.gif'], '', $file->getClientOriginalName());

        $slug = @str_slug($title);
        if (!$slug) {
            $slug = str_random(9);
        }

        sleep(1);
        $approved_at = Carbon::now();
        $media = new Media();
        $media->user_id = $request->user()->id;
        $media->main_image = $mediaName . "." . $mimetype;
        $media->name = $mediaName;
        $media->title = $title;
        $media->slug = $slug;
        $media->type = $mimetype;
        $media->tags = $tags;
        $media->description = $description;
        $media->approved_at = $approved_at;
        $media->save();


        $info_data = [
            'cover_image' => '',
        ];

        $info = new MediaInfo($info_data);
        $media->info()->create($info_data);


        return [
            'files' => [
                0 => ['success'      => 'Uploaded',
                      'successSlug'  => route('media', ['id' => $media->id, 'slug' => $media->slug]),
                      'successTitle' => ucfirst($media->title),
                      'thumbnail'    => Resize::img($media->main_image, 'listingMedia')
                ]
            ]
        ];
    }


    public function doClone($id)
    {

        $source = Media::findOrFail($id);
        $media = $source->replicate();

        $media->title = $media->title . ' (clon)';
        $media->slug = $media->slug . '-clon';

        $media->push();

        // replicar el 1:1 con info
        $new_info = $source->info->replicate();
        $media->info()->save($new_info);

        // insertar clon en las mismas categorias que el master
        foreach($source->categories as $category){
            $media->categories()->attach($category);
        }

        $title = t('Edit the Clone');

        $categories = MediaCategory::items();
        foreach ($categories as $c) {
            if($media->hasCategory($c->id)){
                $c->checked = 'checked';
            }
        }

        return redirect()->route('admin.media.edit',['id' => $media->id])->with('flashSuccess', 'Clonado');
    }



}
