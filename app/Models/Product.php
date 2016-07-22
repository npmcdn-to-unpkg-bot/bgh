<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class product extends Model
{
    // use SoftDeletes;

    protected $table = 'products';
    protected $softDelete = true;
    protected $dates = ['deleted_at', 'featured_at'];


    // protected $connection = 'mysql2';

    // public function changeConnection($conn)
    // {
    //     $this->connection = $conn;
    // }



    public static function scopeApproved()
    {
        return static::whereNotNull('approved_at');
    }

    public static function scopeProfiled()
    {
        return static::whereIn('profile_id', [1, 2]);
    }


    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

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
