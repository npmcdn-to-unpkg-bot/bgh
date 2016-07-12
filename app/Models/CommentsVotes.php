<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class CommentsVotes extends Model
{

    protected $table = 'comments_votes';

    /**
     * @return mixed
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
