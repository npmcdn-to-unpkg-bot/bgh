<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class Follow extends Model
{

    /**
     * @var string
     */
    protected $table = 'follows';

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function followingUser()
    {
        return $this->belongsTo(User::class, 'follow_id');
    }

    /**
     * @return mixed
     */
    public function images()
    {
        return $this->hasMany(Image::class, 'user_id', 'follow_id');
    }
}
