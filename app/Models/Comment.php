<?php
namespace App\Models;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{

    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'comments';
    /**
     * @var bool
     */
    protected $softDelete = true;

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
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @return mixed
     */
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    /**
     * @return mixed
     */
    public function votes()
    {
        return $this->hasMany(CommentsVotes::class, 'comment_id');
    }
}
