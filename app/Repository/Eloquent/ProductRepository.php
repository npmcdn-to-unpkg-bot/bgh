<?php

namespace App\Repository\Eloquent;

use App\Helpers\ResizeHelper;
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

    public function __construct(Product $products, ProductNotifier $notice)
    {
        $this->products = $products;
        // $this->category = $category;
        $this->notification = $notice;
        // $this->favorite = $favorite;
    }

    public function getById($id)
    {
        // return $this->posts()->where('id', $id)->with('user', 'comments', 'comments.replies', 'favorites', 'info')->firstOrFail();
        return $this->posts()->where('id', $id)->with('user', 'info')->firstOrFail();
    }

    private function posts($timeframe = null)
    {

        // si es admin o superadmin no limitar el universo a los aprobados
        if(auth()->check() && (auth()->user()->isSuper() || auth()->user()->isAdmin())){
            $posts = $this->products;
        }
        else{
            $posts = $this->products->approved()->published();
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

    public function getLatest($timeframe = null)
    {
        $products = $this->posts($timeframe)->orderBy('approved_at', 'desc')->with('user'); //->with('user', 'comments', 'favorites');

        return $products->paginate(perPage());
    }

    public function getFeatured($timeframe = null)
    {
        $products = $this->posts($timeframe)->whereNotNull('featured_at')->orderBy('featured_at', 'dec')->with('user'); //->with('user', 'comments', 'favorites');

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

    public function popular($timeframe = null)
    {
        // $products = $this->posts($category, $timeframe)
        //     ->leftJoin('comments', 'comments.product_id', '=', 'products.id')
        //     ->leftJoin('favorites', 'favorites.product_id', '=', 'products.id')
        //     ->select('products.*', DB::raw('count(comments.product_id)*5 + products.views as popular'))
        //     ->groupBy('products.id')->with('user', 'comments', 'favorites')->orderBy('popular', 'desc')
        //     ->paginate(perPage());


        $products = $this->posts($timeframe)
            ->select('products.*', DB::raw('10 as popular'))
            ->groupBy('products.id')->with('user')->orderBy('popular', 'desc')
            ->paginate(perPage());


        return $products;
    }

    public function mostViewed($timeframe = null)
    {
        // $products = $this->posts($category, $timeframe)->orderBy('views', 'desc')->with('user', 'comments', 'favorites')->paginate(perPage());
        $products = $this->posts($timeframe)->orderBy('views', 'desc')->with('user')->paginate(perPage());

        return $products;
    }

    public function search($search, $timeframe = null)
    {
        $extends = explode(' ', $search);

        $products = $this->posts($timeframe)->where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('tags', 'LIKE', '%' . $search . '%')
            ->whereNull('deleted_at')->whereNotNull('approved_at')->orderBy('approved_at', 'desc');

        foreach ($extends as $extend) {

            $products->orWhere('tags', 'LIKE', '%' . $extend . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                ->orWhere('title', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                ->orWhere('description', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at');

        }

        return $products = $products->with('user')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
        // return $products = $products->with('user', 'comments', 'favorites')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
    }

}
