<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class ReplyVotes extends Model
{

    /**
     * @var string
     */
    protected $table = 'replies_votes';

    /**
     * @return mixed
     */
    public function reply()
    {
        return $this->belongsTo(Reply::class, 'reply_id');
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
