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

}
