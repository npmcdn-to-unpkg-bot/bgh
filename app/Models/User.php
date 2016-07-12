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
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'email_confirmation', 'remember_token'];
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */

    protected $softDelete = true;

    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'featured_at'];

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * @param string $value
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * @return mixed
     */
    public function scopeConfirmed()
    {
        return static::whereNotNull('confirmed_at');
    }

    /**
     * @param $value
     * @return string
     */
    public function getFullnameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * @return mixed
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * @return mixed
     */
    public function latestImages()
    {
        return $this->hasMany(Image::class)->orderBy('approved_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return mixed
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function followers()
    {
        return $this->hasMany(Follow::class, 'follow_id');
    }

    /**
     * @return mixed
     */
    public function following()
    {
        return $this->hasMany(Follow::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function votes()
    {
        return $this->hasMany('Votes', 'user_id');
    }
}
