<?php
namespace App\Http\Controllers;

use App\Repository\CommentsRepositoryInterface;
use App\Http\Requests\Comment\CreateRequest;
use App\Http\Requests\Comment\Vote;
use Illuminate\Http\Request;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class CommentController extends Controller
{

    public function __construct(CommentsRepositoryInterface $comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return string
     */
    public function postDeleteComment(Request $request)
    {
        if ($this->comments->delete($request->get('id'))) {
            return 'success';
        }

        return 'failed';
    }


    public function postComment(CreateRequest $request)
    {
        $this->comments->create($request);

        return redirect()->back()->with('flashSuccess', t('Your comment is added'));
    }

    /**
     * @param Vote $request
     * @return mixed
     */
    public function vote(Vote $request)
    {
        $votes = $this->comments->vote($request);

        return response($votes, 200);
    }

}
