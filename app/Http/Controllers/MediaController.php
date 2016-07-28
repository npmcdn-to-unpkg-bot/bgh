<?php
namespace App\Http\Controllers;

use App\Helpers\ResizeHelper;
use App\Repository\MediaRepositoryInterface;
use App\Http\Requests\Media\EditRequest;
use Illuminate\Support\Facades\Crypt;

class MediaController extends Controller
{

    public function __construct(MediaRepositoryInterface $media)
    {
        $this->media = $media;
    }

    public function getIndex($id, $slug = null)
    {

        $media = $this->media->getById($id);

        if (empty($slug) or $slug != $media->slug) {
            return redirect()->route('media', ['id' => $media->id, 'slug' => $media->slug], 301);
        }

        $title = ucfirst($media->title);

        return view('media.view', compact('media', 'title'));
    }

    public function download($id)
    {
        $id = Crypt::decrypt($id);
        $media = $this->media->getById($id);

        if (auth()->user()->id != $media->user_id) {
            $media->downloads = $media->downloads + 1;
            $media->save();
        }
        $file = new ResizeHelper($media->main_image, 'uploads/medias');
        $file = $file->download();

        return response()->download($file, $media->slug . '.' . $media->type, ['content-type' => 'image/jpg'])->deleteFileAfterSend(true);
    }

    public function getByTags($tag)
    {
        $medias = $this->medias->getByTags($tag);
        $title = sprintf('%s %s', t('Tagged With'), ucfirst($tag));

        return view('gallery.index', compact('medias', 'title'));
    }

    public function search(Request $request)
    {
        $this->validate($request, ['q' => 'required']);

        $medias = $this->medias->search($request->get('q'), $request->get('category'), $request->get('timeframe'));

        $title = sprintf('%s %s', t('Searching for'), ucfirst($request->get('q')));

        return view('gallery.index', compact('title', 'medias'));
    }

}
