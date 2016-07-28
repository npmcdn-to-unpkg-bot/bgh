<?php

namespace App\Repository\Eloquent;

use App\Helpers\ResizeHelper;
use App\Models\Media;
use App\Repository\MediaRepositoryInterface;
use Auth;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Support\Facades\Cache;

class MediaRepository implements MediaRepositoryInterface
{

    public function __construct(Media $medias)
    {
        $this->medias = $medias;
    }

    public function getById($id)
    {
        // return $this->posts()->where('id', $id)->with('user', 'comments', 'comments.replies', 'favorites', 'info')->firstOrFail();
        return $this->posts()->where('id', $id)->with('user', 'info')->firstOrFail();
    }

    private function posts($timeframe = null)
    {

        $posts = $this->medias;

        if ($this->resolveTime($timeframe)) {
            $posts = $posts->whereBetween('created_at', $this->resolveTime($timeframe));
        }

        return $posts;
    }

    private function resolveTime($time)
    {
        switch ($time) {
            case 'today':
                $time = [Carbon::now()->subHours(24)->toDateTimeString(), Carbon::now()->toDateTimeString()];
                break;
            case 'week':
                $time = [Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->toDateTimeString()];
                break;
            case 'month':
                $time = [Carbon::now()->subDays(30)->toDateTimeString(), Carbon::now()->toDateTimeString()];
                break;
            case 'year':
                $time = [Carbon::now()->subDays(365)->toDateTimeString(), Carbon::now()->toDateTimeString()];
                break;
            default:
                $time = null;
        }

        return $time;
    }


    public function getByTags($tag)
    {
        $medias = $this->posts()->where('tags', 'LIKE', '%' . $tag . '%')->orderBy('approved_at', 'desc')->with('user');

        return $medias->paginate(perPage());
    }


    public function search($search, $timeframe = null)
    {
        $extends = explode(' ', $search);

        $medias = $this->posts($timeframe)->where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('tags', 'LIKE', '%' . $search . '%')
            ->whereNull('deleted_at')->whereNotNull('approved_at')->orderBy('approved_at', 'desc');

        foreach ($extends as $extend) {

            $medias->orWhere('tags', 'LIKE', '%' . $extend . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                ->orWhere('title', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                ->orWhere('description', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at');

        }

        return $medias = $medias->with('user')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
        // return $medias = $medias->with('user', 'comments', 'favorites')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
    }

}
