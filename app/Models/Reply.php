<?php
namespace App\Models;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'replies';
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
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    /**
     * @return mixed
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * @return mixed
     */
    public function votes()
    {
        return $this->hasMany(ReplyVotes::class, 'reply_id');
    }
}
