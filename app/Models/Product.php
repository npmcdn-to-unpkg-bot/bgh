<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Profiled
{
    // use SoftDeletes;

    protected $table = 'products';
    protected $softDelete = true;
    protected $dates = ['deleted_at', 'featured_at'];


    public static function scopeApproved()
    {
        return static::whereNotNull('approved_at');
    }

    public static function scopePublished()
    {
        return static::where('published',1);
    }

    public function info()
    {
        return $this->hasOne(ProductInfo::class, 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\ProductCategory', 'product_x_category', 'product_id', 'product_category_id');
    }

    public function hasCategory($id){
        return ! $this->categories->filter(function($category) use ($id)
        {
            return $category->id == $id;
        })->isEmpty();
    }

    public function getLink()
    {
        $res = new \stdClass();

        if($this->is_microsite==1){
            $res->url = $this->microsite;
            $res->target = '_blank';
        }
        else{
            $res->url = route('product', ['id' => $this->id, 'slug' => $this->slug]);;
            $res->target = '_self';
        }

        return $res;
    }


}
