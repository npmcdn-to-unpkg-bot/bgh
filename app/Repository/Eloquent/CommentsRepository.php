<?php
namespace App\Repository\Eloquent;

use App\Models\Comment;
use App\Models\CommentsVotes;
use App\Models\Product;
use App\Notifier\ProductNotifier;
use App\Repository\CommentsRepositoryInterface;
use Auth;

class CommentsRepository extends AbstractRepository implements CommentsRepositoryInterface
{

    public function __construct(Comment $comment, Product $products, ProductNotifier $notifications, CommentsVotes $votes)
    {
        $this->model = $comment;
        $this->products = $products;
        $this->notifications = $notifications;
        $this->votes = $votes;
    }

    public function create($request)
    {
        $comment = $this->getNew();
        $comment->user_id = $request->user()->id;
        $comment->image_id = $request->route('id');
        $comment->comment = preg_replace("/[\r\n]+/", "\n", $request->get('comment'));

        $comment->save();
        if ($request->user()->id != $comment->image->user_id) {
            $this->notifications->comment($comment->image, $request->user(), $request->get('comment'));
        }

        return true;
    }

    public function delete($id)
    {
        $commentOwner = $this->model->whereId($id)->first();
        if (!$commentOwner) {
            return false;
        }
        if ($commentOwner->user_id == auth()->user()->id || auth()->user()->id == $commentOwner->image->user->id) {
            $commentOwner->votes()->delete();
            $commentOwner->delete();

            return true;
        }

        return false;
    }

    public function vote($request)
    {
        $comment = $this->getById($request->get('id'));
        $vote = $comment->votes()->whereUserId($request->user()->id)->first();
        if ($vote !== null) {
            $vote->delete();

            return $comment->votes()->count();
        }
        $vote = $this->votes->newInstance();
        $vote->comment_id = $request->get('id');
        $vote->user_id = $request->user()->id;
        $vote->save();

        return $comment->votes()->count();
    }

    public function getById($id)
    {
        $comment = $this->model->whereId($id)->firstOrFail();

        return $comment;
    }
}
