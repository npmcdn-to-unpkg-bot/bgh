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

    public function getLatest($category = null, $timeframe = null)
    {
        $pages = $this->posts($category, $timeframe)->orderBy('approved_at', 'desc')->with('user');
        // $pages = $this->posts($category, $timeframe)->orderBy('approved_at', 'desc')->with('user', 'comments', 'favorites');

        return $pages->paginate(perPage());
    }



    public function getByTags($tag)
    {
        $pages = $this->posts()->where('tags', 'LIKE', '%' . $tag . '%')->orderBy('approved_at', 'desc')->with('user');

        return $pages->paginate(perPage());
    }

    public function incrementViews($page)
    {
        $page->views = $page->views + 1;
        $page->timestamps = false;
        $page->save(['updated_at' => false]);

        return $page;
    }


    public function mostViewed($category = null, $timeframe = null)
    {
        // $pages = $this->posts($category, $timeframe)->orderBy('views', 'desc')->with('user', 'comments', 'favorites')->paginate(perPage());
        $pages = $this->posts($category, $timeframe)->orderBy('views', 'desc')->with('user')->paginate(perPage());

        return $pages;
    }

    public function search($search, $category = null, $timeframe = null)
    {
        $extends = explode(' ', $search);
        if ($category) {
            $categoryId = $this->category->whereSlug($category)->first();
        }
        $pages = $this->posts($category, $timeframe)->where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('tags', 'LIKE', '%' . $search . '%')
            ->whereNull('deleted_at')->whereNotNull('approved_at')->orderBy('approved_at', 'desc');

        foreach ($extends as $extend) {
            if (isset($categoryId)) {
                $pages->whereCategoryId($categoryId)->Where('tags', 'LIKE', '%' . $extend . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->whereCategoryId($categoryId)->orWhere('title', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->whereCategoryId($categoryId)->orWhere('description', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at');
            } else {
                $pages->orWhere('tags', 'LIKE', '%' . $extend . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->orWhere('title', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at');
            }
        }

        return $pages = $pages->with('user')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
        // return $pages = $pages->with('user', 'comments', 'favorites')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
    }

}
