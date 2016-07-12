<?php

namespace App\Repository\Eloquent;

use App\Models\ProductCategory;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Support\Facades\URL;
use Roumen\Feed\Facades\Feed;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{


    protected $model;

    public function  __construct(ProductCategory $model, ProductRepositoryInterface $products)
    {

        $this->model = $model;
        $this->products = $products;
    }

    public function getBySlug($slug)
    {
        $category = $this->model->whereSlug($slug)->firstOrFail();

        return $category;
    }

    public function getAncestors($slug)
    {
        $res = [];

        $category = $this->model->whereSlug($slug)->firstOrFail();
        if($category->parent_id>0){

            while ($category->parent_id > 0) {
                array_unshift($res, $category);
                $category = $this->model->find($category->parent_id);
            }

            array_unshift($res, $category);
        }

        return $res;
    }

    public function getLink($slug)
    {
        $res = '';

        $category = $this->model->whereSlug($slug)->firstOrFail();
        if($category->parent_id>0){
            while ($category->parent_id > 0) {
                $res = $category->slug . "/" . $res;
                $category = $this->model->find($category->parent_id);
            }
        }

        $res = $category->slug . "/" . $res;

        return $res;
    }


    function getItems()
    {
        // return Cache::rememberForever('product_categories', function () {
            return ProductCategory::orderBy('lft', 'asc')->get();
        // });
    }


}
