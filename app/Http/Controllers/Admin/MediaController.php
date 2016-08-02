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

use Thumbnail;

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

        $media = Media::select([
            'media.*','users.fullname as user_fullname','profiles.title as profile_name'
        ])
        ->leftJoin('users', 'users.id', '=', 'media.user_id')
        ->leftJoin('profiles', 'profiles.id', '=', 'media.profile_id');


        // si no es superadmin, filtro el lote por los perfiles que el usuario posea
        if(!auth()->user()->isSuper()){
            $media->whereIn('profile_id', auth()->user()->profiles()->lists('id')); // lo segundo es un array de ids
        }

        // switch ($request->get('type')) {
        //     case 'approved':
        //         $media->approved(); // es del scopeApproved en el Model
        //         break;
        //     case 'approvalRequired':
        //         $media->whereNull('media.approved_at');
        //         break;
        //     default:
        //         $media->approved();
        // }


        $datatables = app('datatables')->of($media);

        $datatables->addColumn('actions', function ($media) {
            return '
            <div class="btn-group pull-right btn-group-sm" role="group" aria-label="Actions">
                <a href="' . route('admin.media.edit', [$media->id]) . '" class="btn btn-default"><i class="fa fa-edit"></i> Edit </a>
                <a href="' . route('admin.media.use', [$media->id]) . '" class="btn btn-default"><i class="fa fa-share-alt"></i> Use</a>
                <a href="' . route('media', [$media->id, $media->slug]) . '" class="btn btn-default" target="_blank"><i class="fa fa-eye"></i> View</a>
                <a href="' . route('media', [$media->id, $media->slug, 'download']) . '" class="btn btn-default"><i class="fa fa-download"></i> Download</a>
                <a href="' . route('admin.media.edit', [$media->id]) . '" class="btn btn-default" rel="delete"><i class="fa fa-trash"></i> Delete</a>
            </div>';
        });

        return $datatables->addColumn('thumbnail', function ($media) {
            return '<img src="' . Resize::img($media->thumbnail, 'listingMedia') . '"/>';
        })
            ->editColumn('created_at', '{!! $created_at->diffForHumans() !!}')
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->addColumn('user', '{!! $user_fullname !!}')
            ->addColumn('profile', '{!! $profile_name !!}')
            ->make(true);
    }


    public function getUse($id)
    {
        $media = Media::whereId($id)->with('info')->firstOrFail();

        $title = t('Use');

        return view('admin.media.use', compact('media', 'title'));
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


        // if ($request->hasFile('name')){
        //     if ($request->file('name')->isValid()){
        //         $save = new ResizeHelper($request->file('name'), 'uploads/media');
        //         list($fName, $fType) = $save->saveOriginal();
        //         $item->name = $fName . "." . $fType;
        //     }
        // }

        if ($request->hasFile('thumbnail')){
            if ($request->file('thumbnail')->isValid()){
                $save = new ResizeHelper($request->file('thumbnail'), 'uploads/media');
                list($fName, $fType) = $save->saveOriginal();
                $item->thumbnail = $fName . "." . $fType;
            }
        }


        $item->save();


        // if ($request->hasFile('cover_image')){
        //     if ($request->file('cover_image')->isValid()){
        //         $save = new ResizeHelper($request->file('cover_image'), 'uploads/media');
        //         list($fName, $fType) = $save->saveOriginal();
        //         $item->info->cover_image = $fName . "." . $fType;
        //         // $request->file('cover_image')->move($destinationPath, $fileName);
        //     }
        // }

        $item->info->save();


        if ($request->ajax() || $request->wantsJson()) {
            // return response()->json(['dato' => 'valor', 'otrodato' => 'otrovalor']);
            return new JsonResponse('ajax todo ok', 200);
        }
        else{
            return redirect()->back()->with('flashSuccess', 'post todo ok');
        }

    }


    // public function put(Request $request)
    // {
    //     $item = new Media();
    //     $item->title = $request->get('title');

    //     $slug = @str_slug($request->get('slug'));
    //     if (!$slug) {
    //         $slug = str_random(7);
    //     }
    //     $item->slug = $slug;

    //     $item->user_id = auth()->user()->id;

    //     $item->save();

    //     $info = [
    //         'cover_image' => '',
    //     ];

    //     $info = new MediaInfo($info);
    //     $item->info()->create($info);

    //     return redirect()->route('admin.media.edit', ['id' => $item->id])->with('flashSuccess', 'Media is now crated');
    // }


    public function delete($id)
    {

        $item = Media::findOrFail($id);

        if(!$item->canHandle()){
            return redirect()->route('admin')->with('flashSuccess', 'sin acceso a editar este mediao');
        }

        $delete = new ResizeHelper( $item->name );
        $delete->delete();

        $item->info()->delete();
        $item->delete();

        return redirect()->route('admin.media')->with('flashSuccess', 'deleted');
    }




    public function clearCache($id)
    {

        $media = Media::whereId($id);

        if(!isset($media->name)){
           $media->name = 'default.png';
        }

        $cache = new ResizeHelper($media->name);
        $cache->clearCache();
        return 'Cache is cleared, reload the page';


    }

    public function getBulkUpload()
    {
        $title = t('Add');

        return view('admin.media.bulkupload', compact('title'));
    }

    public function postBulkUpload(Request $request)
    {
        $file = $request->file('files')[0];

        $tags = null;
        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        }

        $description = $request->get('description');

        $original = $file->getClientOriginalName();

        $tmp = @explode('.', $original);
        $title = $tmp[0];
        $ext = $tmp[1];


        $slug = @str_slug($title);
        if (!$slug) {
            $slug = str_random(9);
        }

        $mimetype = $file->getClientMimeType();

        $type = $this->getTypeOfExtension($ext);

        // var_dump($title);
        // var_dump($ext);
        // var_dump($mimetype);
        // var_dump($type);
        // exit();


        $info = [
            'mime_type'     => $mimetype,
            'original'      => $original,
        ];

        if($type=="image"){

            $save = new ResizeHelper($file, 'uploads/media');
            list($mediaName, $extension, $real) = $save->saveOriginal();
            $newname = $mediaName . "." . $extension;
            $real = base_path() . $real;
            $exif = exif_read_data($real, 0, true);

            $resolution = (isset($exif['IFD0']['XResolution']) && strlen($exif['IFD0']['XResolution']) > 0 ? $exif['IFD0']['XResolution'] : null);
            if($resolution!=null){
                $tmp = @explode('/', $resolution);
                $resolution = intval($tmp[0]) / intval($tmp[1]);
            }

            $focal = (isset($exif['EXIF']['FocalLength']) && strlen($exif['EXIF']['FocalLength']) > 0 ? $exif['EXIF']['FocalLength'] : null);
            if($focal!=null){
                $tmp = @explode('/', $focal);
                $focal = intval($tmp[0]);
            }

            $taken_at = (isset($exif['EXIF']['DateTimeOriginal']) && strlen($exif['EXIF']['DateTimeOriginal']) > 0 ? $exif['EXIF']['DateTimeOriginal'] : null);
            if($taken_at!=null){
                $taken_at = strtotime($taken_at);
            }

            $width = intval(isset($exif['COMPUTED']['Width']) && strlen($exif['COMPUTED']['Width']) > 0 ? $exif['COMPUTED']['Width'] : 0);
            $height = intval(isset($exif['COMPUTED']['Height']) && strlen($exif['COMPUTED']['Height']) > 0 ? $exif['COMPUTED']['Height'] : 0);

            if($width==$height){
                $orientation = 'square';
            }
            elseif($width<$height){
                $orientation = 'portrait';
            }
            else{
                $orientation = 'landscape';
            }

            $info['orientation'] = $orientation;
            $info['width'] = $width;
            $info['height'] = $height;
            $info['camera'] = (isset($exif['IFD0']['Model']) && strlen($exif['IFD0']['Model']) > 0 ? $exif['IFD0']['Model'] : null);
            $info['focal_length'] = $focal;
            $info['shutter_speed'] = (isset($exif['EXIF']['ExposureTime']) && strlen($exif['EXIF']['ExposureTime']) > 0 ? $exif['EXIF']['ExposureTime'] : null);
            $info['aperture'] = (isset($exif['COMPUTED']['ApertureFNumber']) && strlen($exif['COMPUTED']['ApertureFNumber']) > 0 ? $exif['COMPUTED']['ApertureFNumber'] : null);
            $info['iso'] = (isset($exif['EXIF']['ISOSpeedRatings']) && strlen($exif['EXIF']['ISOSpeedRatings']) > 0 ? $exif['EXIF']['ISOSpeedRatings'] : null);
            $info['copyright'] = (isset($exif['COMPUTED']['Copyright']) && strlen($exif['COMPUTED']['Copyright']) > 0 ? $exif['COMPUTED']['Copyright'] : null);
            $info['resolution'] = $resolution;
            $info['software'] = (isset($exif['IFD0']['Software']) && strlen($exif['IFD0']['Software']) > 0 ? $exif['IFD0']['Software'] : null);
            $info['taken_at'] = $taken_at;

            $thumbnail = $newname;

        }
        else if($type=="video"){

            $fileName = str_random(9);
            $newname = $fileName . "." . $ext;
            $extension = $ext;

            $file->move('uploads/media', $newname);

            $video_path = base_path() . '/uploads/media/' . $newname;
            $thumbnail_path = base_path() . '/uploads/media/';
            $thumbnail_image = $fileName . '.png';

            // https://github.com/lakshmajim/Thumbnail#installation
            // $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image,160,128,2,$water_mark,true);
            $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image);
            if($thumbnail_status){
                $thumbnail = $thumbnail_image;
            }
            else{
                $thumbnail = '';
            }

        }

        sleep(1);

        $media = new Media();
        $media->user_id = $request->user()->id;
        $media->name = $newname;
        $media->thumbnail = $thumbnail;
        $media->title = $title;
        $media->slug = $slug;
        $media->type = $type;
        $media->extension = $extension;
        $media->tags = $tags;
        $media->description = $description;
        $media->save();

        $media->info()->create($info);

        return [
            'files' => [
                0 => ['success'      => 'Uploaded',
                      'successSlug'  => route('media', ['id' => $media->id, 'slug' => $media->slug]),
                      'successTitle' => ucfirst($media->title),
                      'thumbnail'    => Resize::img($media->thumbnail, 'listingMedia')
                ]
            ]
        ];
    }


    private function getTypeOfExtension($ext){

        $mime_types = array(

            'png' => 'image',
            'jpe' => 'image',
            'jpeg' => 'image',
            'jpg' => 'image',
            'gif' => 'image',
            'bmp' => 'image',
            'ico' => 'image',
            'tiff' => 'image',
            'tif' => 'image',
            'svg' => 'image',
            'svgz' => 'image',

            'zip' => 'compressed',
            'rar' => 'compressed',

            'mp3' => 'audio',

            'qt' => 'video',
            'mov' => 'video',
            'mp4' => 'video',
            'wmv' => 'video',

            'pdf' => 'adobe',
            'psd' => 'adobe',
            'ai' => 'adobe',
            'eps' => 'adobe',
            'ps' => 'adobe',

            'doc' => 'office',
            'rtf' => 'office',
            'xls' => 'office',
            'ppt' => 'office',
            'odt' => 'office',
            'ods' => 'office',

        );

        return $mime_types[strtolower($ext)];

    }



}
