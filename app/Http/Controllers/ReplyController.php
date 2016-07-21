<?php
namespace App\Http\Controllers;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

use App\Helpers\Resize;
use App\Repository\ReplyRepositoryInterface;
use App\Http\Requests\Comment\CreateReply;
use App\Http\Requests\Comment\ReplyVote;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct(ReplyRepositoryInterface $reply)
    {
        $this->reply = $reply;
    }

    /**
     * @return string
     */
    public function delete(Request $request)
    {
        if ($this->reply->delete($request)) {
            return 'success';
        }

        return 'false';
    }

    /**
     * @return string
     */
    public function postReply(CreateReply $request)
    {
        $reply = $this->reply->createReply($request);

        return response()->json([
            'fullname' => e($request->user()->fullname),
            'profile_link' => $request->user()->username,
            'profile_avatar' => sprintf('%s', Resize::img($request->user()->avatar, 'avatar')),
            'description' => nl2br(e($reply->reply)),
            'time' => $reply->created_at->diffForHumans(),
            'comment_id' => $reply->comment_id,
            'reply' => e($reply->reply),
        ]);
    }

    /**
     * @return mixed
     */
    public function vote(ReplyVote $request)
    {
        $votes = $this->reply->vote($request);

        return response($votes, 200);
    }
}
