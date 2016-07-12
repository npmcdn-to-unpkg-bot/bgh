<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Http\Controllers\Admin\Comment;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        $title = sprintf('List of %s comments', ucfirst($request->get('type')));
        $type = $request->get('type');

        return view('admin.comment.index', compact('title', 'type'));
    }

    /**
     * @param $id
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function getEdit($id)
    {
        $title = 'Editing Comment';
        $comment = Comment::whereId($id)->with('image', 'user', 'replies', 'votes')->first();

        return view('admin.comment.edit', compact('title', 'comment'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit($id, Request $request)
    {
        $comment = Comment::whereId($id)->with('image', 'user', 'replies', 'votes')->first();
        if ($request->get('delete')) {
            $comment->replies()->delete();
            $comment->votes()->forceDelete();
            $comment->delete();

            return redirect()->route('admin.comments')->with('flashSuccess', 'Comment is now deleted');
        }
        $comment->comment = $request->get('comment');
        $comment->save();

        return redirect()->back()->with('flashSuccess', 'Comment is now updated');
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        $comments = Comment::select([
            'comments.*',
            DB::raw('count(comments_votes.comment_id) as votes'),
            DB::raw('users.fullname as fullname'),
            DB::raw('users.username as username'),
            DB::raw('users.avatar as avatar'),
        ])->leftJoin('comments_votes', 'comments_votes.comment_id', '=', 'comments.id')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->groupBy('comments.id');

        $datatables = app('datatables')->of($comments);

        $datatables->addColumn('actions', function ($comment) {
            return '<a href="' . route('admin.comments.edit', [$comment->id]) . '" class="btn btn-info" target="_blank"><i class="fa fa-edit"></i> Edit </a>';
        });

        return $datatables
            ->editColumn('username', '{!! link_to_route("user", $username, [$username]) !!}')
            ->editColumn('image_id', '{!! link_to_route("image", $image_id, [$image_id]) !!}')
            ->editColumn('created_at', '{!! $created_at->diffForHumans() !!}')
            ->editColumn('featured_at', function ($image) {
                if ($image->featured_at !== null) {
                    $image->featured_at->diffForHumans();
                }

                return 'Not Featured';
            })
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->editColumn('fullname', '{!! str_limit($fullname, 60) !!}')
            ->make(true);
    }
}
