<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository\Eloquent;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Reply;
use App\Models\ReplyVotes;
use App\Notifier\ReplyNotifer;
use App\Repository\ReplyRepositoryInterface;
use Auth;

class ReplyRepository extends AbstractRepository implements ReplyRepositoryInterface
{
    /**
     * @param ReplyNotifer $notifications
     * @param Reply $model
     * @param Comment $comment
     * @param Product $products
     * @internal param Reply $reply
     */
    public function __construct(ReplyNotifer $notifications, Reply $model, Comment $comment, Product $products, ReplyVotes $vote)
    {
        $this->notification = $notifications;
        $this->model = $model;
        $this->comment = $comment;
        $this->products = $products;
        $this->vote = $vote;
    }

    /**
     * @param $request
     * @return mixed|void
     */
    public function createReply($request)
    {
        $comment = $this->comment->whereId($request->get('reply_msgid'))->firstOrFail();
        $this->products->whereId($comment->image_id)->firstOrFail();

        $reply = $this->getNew();
        $reply->user_id = $request->user()->id;
        $reply->image_id = $comment->image_id;
        $reply->comment_id = $comment->id;
        $reply->reply = $request->get('textcontent');
        $reply->save();

        if ($reply->comment->user->id != $request->user()->id) {
            $this->notification->replyNotice($reply->comment->user, $request->user(), $comment->image, $request->get('textcontent'), true);
        }

        $noticeSentToUsers = [];
        foreach ($reply->whereCommentId($request->get('reply_msgid'))->get() as $replier) {
            if ($replier->user_id != $request->user()->id and $replier->user_id != $comment->user_id && !in_array($replier->user_id, $noticeSentToUsers)) {
                $this->notification->replyNotice($replier->user, $request->user(), $comment->image, $request->get('textcontent'));
                $noticeSentToUsers[] = $replier->user_id;
            }
        }

        return $reply;
    }

    /**
     * @param $input
     * @return bool
     */
    public function delete($request)
    {
        $reply = $this->model->whereId($request->get('id'))->first();
        if (!$reply) {
            return false;
        }
        if ($reply->user_id == auth()->user()->id || $reply->comment->user->id == auth()->user()->id || $reply->image->user->id == auth()->user()->id) {
            $reply->votes()->delete();
            $reply->delete();

            return true;
        }
    }

    /**
     * @param $replyId
     * @return mixed
     */
    public function vote($request)
    {
        $reply = $this->getById($request->get('id'));
        $vote = $reply->votes()->whereUserId($request->user()->id)->first();
        if ($vote !== null) {
            $vote->delete();

            return $reply->votes()->count();
        }
        $vote = $this->vote->newInstance();
        $vote->reply_id = $request->get('id');
        $vote->user_id = $request->user()->id;
        $vote->save();

        return $reply->votes()->count();
    }

    /**
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $reply = $this->model->whereId($id)->firstOrFail();

        return $reply;
    }
}
