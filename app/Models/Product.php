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

    // public function getTitleAttribute($value)
    // {
    //     return ucfirst($value);
    // }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class, 'product_id');
    // }

    // public function favorites()
    // {
    //     return $this->hasMany(Favorite::class, 'product_id');
    // }

    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id');
    // }

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

}
