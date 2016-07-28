<?php

namespace App\Repository\Eloquent;

use App\Helpers\ResizeHelper;
use App\Models\Page;
use App\Notifier\PageNotifier;
use App\Repository\PageRepositoryInterface;
use Auth;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Support\Facades\Cache;

class PageRepository implements PageRepositoryInterface
{

    public function __construct(Page $pages, PageNotifier $notice)
    {
        $this->pages = $pages;
        $this->notification = $notice;
    }

    public function getById($id)
    {
        return $this->pages->where('id', $id)->with('user', 'info')->firstOrFail();
    }

    public function getBySlug($slug)
    {
        return $this->pages->where('slug', $slug)->with('user')->firstOrFail();
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

    public function getLatest($timeframe = null)
    {
        $pages = $this->posts($timeframe)->orderBy('approved_at', 'desc')->with('user');
        return $pages->paginate(perPage());
    }

    public function getByTags($tag)
    {
        $pages = $this->posts()->where('tags', 'LIKE', '%' . $tag . '%')->orderBy('approved_at', 'desc')->with('user');
        return $pages->paginate(perPage());
    }

    public function incrementViews($item)
    {
        $item->views = $item->views + 1;
        $item->timestamps = false;
        $item->save(['updated_at' => false]);

        return $item;
    }

    public function mostViewed($timeframe = null)
    {
        $pages = $this->posts($timeframe)->orderBy('views', 'desc')->with('user')->paginate(perPage());
        return $pages;
    }

    public function search($search, $timeframe = null)
    {
        $extends = explode(' ', $search);

        $pages = $this->posts($timeframe)->where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('tags', 'LIKE', '%' . $search . '%')
            ->whereNull('deleted_at')->whereNotNull('approved_at')->orderBy('approved_at', 'desc');

        foreach ($extends as $extend) {

            $pages->orWhere('tags', 'LIKE', '%' . $extend . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                ->orWhere('title', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                ->orWhere('description', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at');

        }

        return $pages = $pages->with('user')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
    }

}
