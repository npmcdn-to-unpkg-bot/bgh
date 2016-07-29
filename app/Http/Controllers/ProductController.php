<?php
namespace App\Http\Controllers;

use App\Helpers\ResizeHelper;
use App\Repository\FavoriteRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Http\Requests\Product\EditRequest;
use App\Http\Requests\Product\Favorite;
use Illuminate\Support\Facades\Crypt;

class ProductController extends Controller
{

    public function __construct(ProductRepositoryInterface $product, FavoriteRepositoryInterface $favorite, ProductCategoryRepositoryInterface $category)
    {
        $this->product = $product;
        $this->favorite = $favorite;
        $this->category = $category;
    }

    public function getIndex($id, $slug = null)
    {

        $product = $this->product->getById($id);

        $categories = $product->categories;

        if (empty($slug) or $slug != $product->slug) {
            return redirect()->route('product', ['id' => $product->id, 'slug' => $product->slug], 301);
        }

        \Event::fire('App\Events', $product);

        // $comments = $product->comments()->with('user', 'replies')->orderBy('created_at', 'desc')->paginate(10);

        $title = ucfirst($product->title);

        foreach ($product->categories as $cat) {
            $cat->link = $this->category->getLink($cat->slug);
        }

        return view('product.view', compact('product', 'previous', 'title'));
        // return view('product.view', compact('product', 'comments', 'previous'));
    }

    public function postFavorite(Favorite $request)
    {
        return $this->favorite->favorite($request);
    }

    public function featured(Request $request)
    {
        $products = $this->products->getFeatured($request->get('category'), $request->get('timeframe'));
        $title = t('Featured Images');

        return view('gallery.index', compact('products', 'title'));
    }


    public function mostPopular(Request $request)
    {
        $products = $this->products->popular($request->get('timeframe'));
        $title = t('Popular');

        return view('gallery.index', compact('products', 'title'));
    }

    public function mostViewed(Request $request)
    {
        $products = $this->products->mostViewed($request->get('timeframe'));
        $title = t('Most Viewed');

        return view('gallery.index', compact('products', 'title'));
    }

    public function getByTags($tag)
    {
        $products = $this->products->getByTags($tag);
        $title = sprintf('%s %s', t('Tagged With'), ucfirst($tag));

        return view('gallery.index', compact('products', 'title'));
    }

    public function search(Request $request)
    {
        $this->validate($request, ['q' => 'required']);

        $products = $this->products->search($request->get('q'), $request->get('category'), $request->get('timeframe'));

        $title = sprintf('%s %s', t('Searching for'), ucfirst($request->get('q')));

        return view('gallery.index', compact('title', 'products'));
    }

}
