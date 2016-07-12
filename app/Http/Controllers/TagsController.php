<?php
namespace App\Http\Controllers;

use App\Helpers\Resize;
use App\Repository\ProductRepositoryInterface;
use App\Http\Controllers;
use Roumen\Feed\Facades\Feed;


class TagsController extends Controller
{

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

    public function getIndex($tag)
    {
        // $products = $this->products->getByTags($tag);
        $products = $this->products->getByTags($tag);
        $title = sprintf('%s %s', t('Tagged with'), ucfirst($tag));

        return view('tag.index', compact('products', 'title'));
    }

    public function getRss($tag)
    {

        $products = $this->products->getByTags($tag);
        $feed = Feed::make();
        $feed->title = siteSettings('siteName') . '/tag/' . $tag;
        $feed->description = siteSettings('siteName') . '/tag/' . $tag;
        $feed->link = url('tag/' . $tag);
        $feed->lang = 'en';
        foreach ($products as $post) {
            // set item's title, author, url, pubdate and description
            $desc = '<a href="' . route('image', ['id' => $post->id, 'slug' => $post->slug]) . '"><img src="' . Resize::image($post, 'mainImage') . '" /></a><br/><br/>
                <h2><a href="' . route('image', ['id' => $post->id, 'slug' => $post->slug]) . '">' . e($post->title) . '</a>
                by
                <a href="' . route('user', ['username' => $post->user->username]) . '">' . ucfirst($post->user->fullname) . '</a>
                ( <a href="' . route('user', ['username' => $post->user->username]) . '">' . $post->user->username . '</a> )
                </h2>' . $post->image_description;
            $feed->add(ucfirst(e($post->title)), $post->user->fullname, route('image', ['id' => $post->id, 'slug' => $post->slug]), $post->created_at, $desc);
        }

        return $feed->render('atom');

    }

}
