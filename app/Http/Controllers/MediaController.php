<?php
namespace App\Http\Controllers;

use App\Helpers\Resize;
use App\Helpers\ResizeHelper;
use App\Helpers\VideoStream;

use App\Repository\MediaRepositoryInterface;
use App\Http\Requests\Media\EditRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;


class MediaController extends Controller
{

    public function __construct(MediaRepositoryInterface $media)
    {
        $this->media = $media;
    }

    public function getMedia(Request $request, $id, $slug = null)
    {

        $media = $this->media->getById($id);

        // if (empty($slug) or $slug != $media->slug) {
        //     return redirect()->route('media', ['id' => $media->id, 'slug' => $media->slug], 301);
        // }

        // si existe el parametro view, muestro la pagina con la foto y sus datos
        if ($request->exists('view')) {
            $title = ucfirst($media->title);
            return view('media.view', compact('media', 'title'));
        }
        else{

            switch ($media->type) {

                case 'image':

                    if ($request->has('recipe')) { // el has no toma como valido un ''
                        $recipe = $request->get('recipe');
                    }
                    else{
                        $recipe = 'mainMedia';
                    }

                    if ($request->exists('download')) {
                        return response()->download(Resize::img($media->name,$recipe,true));
                    }
                    else{
                        return response()->file(Resize::img($media->name,$recipe,true));
                    }

                    break;

                case 'video':

                    if ($request->exists('download')) {
                        return response()->download($media->getOriginalPath());
                    }
                    else{
                        $stream = new VideoStream($media->getOriginalPath());
                        $stream->start();
                    }

                    break;

                default:
                    return Response::error('404');

            }

        }

    }

    public function download($id)
    {
        $id = Crypt::decrypt($id);
        $media = $this->media->getById($id);

        $file = new ResizeHelper($media->name, 'uploads/media');
        $file = $file->download();

        return response()->download($file, $media->slug . '.' . $media->type, ['content-type' => 'image/jpg'])->deleteFileAfterSend(true);
    }

    public function getByTags($tag)
    {
        $media = $this->media->getByTags($tag);
        $title = sprintf('%s %s', t('Tagged With'), ucfirst($tag));

        return view('gallery.index', compact('media', 'title'));
    }

    public function search(Request $request)
    {
        $this->validate($request, ['q' => 'required']);

        $media = $this->media->search($request->get('q'), $request->get('category'), $request->get('timeframe'));

        $title = sprintf('%s %s', t('Searching for'), ucfirst($request->get('q')));

        return view('gallery.index', compact('title', 'media'));
    }

}
