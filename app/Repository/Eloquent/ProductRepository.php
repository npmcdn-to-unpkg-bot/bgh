<?php

namespace App\Repository\Eloquent;

use App\Helpers\ResizeHelper;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Notifier\ProductNotifier;
// use App\Repository\FavoriteRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use Auth;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements ProductRepositoryInterface
{

    // public function __construct(Product $products, ProductNotifier $notice, Category $category, FavoriteRepositoryInterface $favorite)
    public function __construct(Product $products, ProductNotifier $notice, ProductCategory $category)
    {
        $this->products = $products;
        $this->category = $category;
        $this->notification = $notice;
        // $this->favorite = $favorite;
    }

    public function getById($id)
    {
        // return $this->posts()->where('id', $id)->with('user', 'comments', 'comments.replies', 'favorites', 'info')->firstOrFail();
        return $this->posts()->where('id', $id)->with('user', 'info')->firstOrFail();
    }

    private function posts($category = null, $timeframe = null)
    {

        $posts = $this->products->approved();

        if ($category = $this->category->whereSlug($category)->first()) {
            $posts = $posts->whereCategoryId($category->id);
        }

        if ($this->resolveTime($timeframe)) {
            $posts = $posts->whereBetween('approved_at', $this->resolveTime($timeframe));
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

    public function getLatest($category = null, $timeframe = null)
    {
        $products = $this->posts($category, $timeframe)->orderBy('approved_at', 'desc')->with('user');
        // $products = $this->posts($category, $timeframe)->orderBy('approved_at', 'desc')->with('user', 'comments', 'favorites');

        return $products->paginate(perPage());
    }

    public function getFeatured($category = null, $timeframe = null)
    {
        // $products = $this->posts($category, $timeframe)->whereNotNull('featured_at')->orderBy('featured_at', 'dec')->with('user', 'comments', 'favorites');
        $products = $this->posts($category, $timeframe)->whereNotNull('featured_at')->orderBy('featured_at', 'dec')->with('user');

        return $products->paginate(perPage());
    }


    public function getByTags($tag)
    {
        $products = $this->posts()->where('tags', 'LIKE', '%' . $tag . '%')->orderBy('approved_at', 'desc')->with('user');

        return $products->paginate(perPage());
    }

    public function incrementViews($product)
    {
        $product->views = $product->views + 1;
        $product->timestamps = false;
        $product->save(['updated_at' => false]);

        return $product;
    }

    // public function mostCommented($category = null, $timeframe = null)
    // {
    //     $products = $this->posts($category, $timeframe)->with('user', 'comments', 'favorites')->approved()->join('comments', 'comments.product_id', '=', 'products.id')
    //         ->select('products.*', DB::raw('count(comments.product_id) as cmts'))
    //         ->groupBy('products.id')->with('user', 'comments', 'favorites')->orderBy('cmts', 'desc')
    //         ->paginate(perPage());;

    //     return $products;
    // }

    public function popular($category = null, $timeframe = null)
    {
        // $products = $this->posts($category, $timeframe)
        //     ->leftJoin('comments', 'comments.product_id', '=', 'products.id')
        //     ->leftJoin('favorites', 'favorites.product_id', '=', 'products.id')
        //     ->select('products.*', DB::raw('count(comments.product_id)*5 + products.views as popular'))
        //     ->groupBy('products.id')->with('user', 'comments', 'favorites')->orderBy('popular', 'desc')
        //     ->paginate(perPage());


        $products = $this->posts($category, $timeframe)
            ->select('products.*', DB::raw('10 as popular'))
            ->groupBy('products.id')->with('user')->orderBy('popular', 'desc')
            ->paginate(perPage());


        return $products;
    }

    // public function mostFavorited($category = null, $timeframe = null)
    // {
    //     $products = $this->posts($category, $timeframe)->join('favorites', 'favorites.product_id', '=', 'products.id')
    //         ->select('products.*', DB::raw('count(favorites.product_id) as favs'))
    //         ->groupBy('products.id')->with('user', 'comments', 'favorites')->orderBy('favs', 'desc')
    //         ->paginate(perPage());

    //     return $products;
    // }

    public function mostDownloaded($category = null, $timeframe = null)
    {
        // $products = $this->posts($category, $timeframe)->orderBy('downloads', 'desc')->with('user', 'comments', 'favorites')->paginate(perPage());

        $products = $this->posts($category, $timeframe)->orderBy('downloads', 'desc')->with('user')->paginate(perPage());

        return $products;
    }

    public function mostViewed($category = null, $timeframe = null)
    {
        // $products = $this->posts($category, $timeframe)->orderBy('views', 'desc')->with('user', 'comments', 'favorites')->paginate(perPage());
        $products = $this->posts($category, $timeframe)->orderBy('views', 'desc')->with('user')->paginate(perPage());

        return $products;
    }

    public function search($search, $category = null, $timeframe = null)
    {
        $extends = explode(' ', $search);
        if ($category) {
            $categoryId = $this->category->whereSlug($category)->first();
        }
        $products = $this->posts($category, $timeframe)->where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('tags', 'LIKE', '%' . $search . '%')
            ->whereNull('deleted_at')->whereNotNull('approved_at')->orderBy('approved_at', 'desc');

        foreach ($extends as $extend) {
            if (isset($categoryId)) {
                $products->whereCategoryId($categoryId)->Where('tags', 'LIKE', '%' . $extend . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->whereCategoryId($categoryId)->orWhere('title', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->whereCategoryId($categoryId)->orWhere('description', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at');
            } else {
                $products->orWhere('tags', 'LIKE', '%' . $extend . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->orWhere('title', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at');
            }
        }

        return $products = $products->with('user')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
        // return $products = $products->with('user', 'comments', 'favorites')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
    }

}
