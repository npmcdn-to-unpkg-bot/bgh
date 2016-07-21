<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Profile extends Model
{

    protected $table = 'profiles';

    protected $dates = ['deleted_at', 'featured_at'];

    protected $softDelete = true;

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_x_profile', 'profile_id', 'user_id');
    }


    public static function items()
    {
        // return Cache::rememberForever('product_categories', function () {
            return Profile::orderBy('lft', 'asc')->get();
        // });
    }



}
