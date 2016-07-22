<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    protected $table = 'users';

    protected $hidden = ['password', 'email_confirmation', 'remember_token'];

    protected $softDelete = true;

    protected $dates = ['deleted_at', 'featured_at'];

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getReminderEmail()
    {
        return $this->email;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function scopeConfirmed()
    {
        return static::whereNotNull('confirmed_at');
    }

    public function getFullnameAttribute($value)
    {
        return ucfirst($value);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function latestProducts()
    {
        return $this->hasMany(Product::class)->orderBy('approved_at', 'desc');
    }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class);
    // }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'follow_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function votes()
    {
        return $this->hasMany('Votes', 'user_id');
    }


    public function profiles()
    {
        return $this->belongsToMany('App\Models\Profile', 'user_x_profile', 'user_id', 'profile_id');
    }

    // public function hasProfile($id){

    //     return ! $this->profiles->filter(function($profile) use ($id)
    //     {
    //         return $profile->id == $id;
    //     })->isEmpty();

    // }

    public function isSuper()
    {
        return $this->profiles()->where('profile_id', 0)->first();
    }

    public function isAdmin()
    {
        // var_dump($this->profiles->pluck('profile_id')->all());
        // exit();
        return $this->profiles()->where('profile_id', 1)->first();
    }




}
