<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductCategory extends Profiled
{

    protected $table = 'product_categories';

    public function getPath()
    {
    	$res = '';

        $c = $this;
        if($c->parent_id>0){
            while ($c->parent_id > 0) {
                $res = $c->slug . "/" . $res;
                $c = $this->find($c->parent_id);
            }
        }

        $res = $c->slug . "/" . $res;

        return $res;
    }

    public function getLink()
    {
    	$res = '';

        $c = $this;
        if($c->parent_id>0){
            while ($c->parent_id > 0) {
                $res = $c->slug . "/" . $res;
                $c = $this->find($c->parent_id);
            }
        }

        $res = route('products') . "/" . $c->slug . "/" . $res;

        return $res;

    }


    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_x_category', 'product_category_id', 'product_id')->orderBy('order', 'asc'); // orden de campos chequeado
    }

    public static function items()
    {
        // return Cache::rememberForever('product_categories', function () {
            return ProductCategory::orderBy('lft', 'asc')->get();
        // });
    }


}
