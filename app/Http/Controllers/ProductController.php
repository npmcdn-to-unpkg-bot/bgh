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

        // foreach ($product->categories as $cat) {
        //     var_dump($cat->name);
        // }

        if (empty($slug) or $slug != $product->slug) {
            return redirect()->route('product', ['id' => $product->id, 'slug' => $product->slug], 301);
        }

        \Event::fire('App\Events', $product);

        // $comments = $product->comments()->with('user', 'replies')->orderBy('created_at', 'desc')->paginate(10);

        // $previous = $this->product->findPreviousProduct($product);
        // $next = $this->product->findNextProduct($product);
        $title = ucfirst($product->title);


        foreach ($product->categories as $cat) {
            $cat->link = $this->category->getLink($cat->slug);
        }

        return view('product.view', compact('product', 'previous', 'next', 'title'));
        // return view('product.view', compact('product', 'comments', 'previous', 'next', 'title'));
    }

    public function download($id)
    {
        $id = Crypt::decrypt($id);
        $product = $this->product->getById($id);

        // if (!$product or (siteSettings('allowDownloadOriginal') == 'leaveToUser' and $product->allow_download != 1)) {
        //     return redirect()->route('gallery')->with('flashError', t('You are not allowed to download this product'));
        // }


        if (auth()->user()->id != $product->user_id) {
            $product->downloads = $product->downloads + 1;
            $product->save();
        }
        $file = new ResizeHelper($product->main_image, 'uploads/products');
        $file = $file->download();

        return response()->download($file, $product->slug . '.' . $product->type, ['content-type' => 'image/jpg'])->deleteFileAfterSend(true);
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

    /**
     * @return mixed
     */
    public function mostCommented(Request $request)
    {
        $products = $this->products->mostCommented($request->get('category'), $request->get('timeframe'));
        $title = t('Most Commented');

        return view('gallery.index', compact('products', 'title'));
    }

    /**
     * @return mixed
     */
    public function mostFavorited(Request $request)
    {
        $products = $this->products->mostFavorited($request->get('category'), $request->get('timeframe'));
        $title = t('Most Favorites');

        return view('gallery.index', compact('products', 'title'));
    }

    /**
     * @return mixed
     */
    public function mostDownloaded(Request $request)
    {
        $products = $this->products->mostDownloaded($request->get('category'), $request->get('timeframe'));
        $title = t('Popular');

        return view('gallery.index', compact('products', 'title'));
    }

    /**
     * @return mixed
     */
    public function mostPopular(Request $request)
    {
        $products = $this->products->popular($request->get('category'), $request->get('timeframe'));
        $title = t('Popular');

        return view('gallery.index', compact('products', 'title'));
    }

    /**
     * @return mixed
     */
    public function mostViewed(Request $request)
    {
        $products = $this->products->mostViewed($request->get('category'), $request->get('timeframe'));
        $title = t('Most Viewed');

        return view('gallery.index', compact('products', 'title'));
    }

    /**
     * @param $tag
     * @return mixed
     */
    public function getByTags($tag)
    {
        $products = $this->products->getByTags($tag);
        $title = sprintf('%s %s', t('Tagged With'), ucfirst($tag));

        return view('gallery.index', compact('products', 'title'));
    }


    /**
     * @return mixed
     */
    public function search(Request $request)
    {
        $this->validate($request, ['q' => 'required']);

        $products = $this->products->search($request->get('q'), $request->get('category'), $request->get('timeframe'));

        $title = sprintf('%s %s', t('Searching for'), ucfirst($request->get('q')));

        return view('gallery.index', compact('title', 'products'));
    }

}
