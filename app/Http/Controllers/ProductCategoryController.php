<?php
namespace App\Http\Controllers;

use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{

    private $category;

    public function __construct(ProductCategoryRepositoryInterface $category, ProductRepositoryInterface $products)
    {
        $this->products = $products;
        $this->category = $category;
    }

    public function getIndex()
    {

        $categories = $this->category->getItems();

        $breadcrum = [];
        $title = t('Products');
        return view('product.index', compact('title', 'breadcrum', 'categories'));
    }

    public function getCategory($category = null)
    {
        if($category==null){
            return $this->getIndex();
        }

        $categories = $this->category->getItems();

        $c = explode('/', $category);
        $main = $this->category->getBySlug(end($c));

        $breadcrum = $this->category->getAncestors($main->slug);

        if(sizeof($breadcrum)==0){
            array_push( $breadcrum , $main);
        }


        if ($main)
        {


            $valid = true;
            foreach ($breadcrum as $i => $category)
            {

                $category->link = $this->category->getLink($category->slug);

                if ($category->slug !== $c[$i])
                {
                    $valid = false;
                    break;
                }

            }

            $category = $main;

            $items = $category->products;

            // var_dump($breadcrum);

            // este if y el código inmediato superior chequean que todos los segmentos de la url se cumplan, para evitar un match por ultimo
            if ($valid)
            {
                $title = $main->name;
                return view('product.category', compact('category', 'title', 'breadcrum', 'categories', 'items'));
            }
            else{
                $breadcrum = [];
                $title = "No encontrada";
                return view('product.category', compact('category', 'title', 'breadcrum', 'categories', 'items'));
            }

        }


    }

}
