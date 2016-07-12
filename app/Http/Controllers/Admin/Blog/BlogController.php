<?php
namespace App\Http\Controllers\Admin\Blog;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class BlogController extends Controller
{
    /**
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function getIndex()
    {
        $title = sprintf('List Of Blogs');

        return view('admin.blog.index', compact('title'));
    }

    /**
     * @param $id
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function getEdit($id)
    {
        $blog = Blog::whereId($id)->firstOrFail();
        $title = sprintf('Editing blog "%s"', $blog->title);

        return view('admin.blog.edit', compact('blog', 'title'));
    }

    /**
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function getCreate()
    {
        $title = 'Creating new blog';

        return view('admin.blog.create', compact('title'));
    }

    /**
     * @param Request $request
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function postCreate(Request $request)
    {
        $blog = new Blog();
        $blog->title = $request->get('title');
        $blog->description = $request->get('description');
        $slug = @str_slug($request->get('title'));
        if (!$slug) {
            $slug = str_random(7);
        }
        $blog->user_id = auth()->user()->id;
        $blog->slug = $slug;
        $blog->save();

        return redirect()->route('admin.blogs')->with('flashSuccess', 'Blog is now crated');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(Request $request)
    {
        $blog = Blog::whereId($request->route('id'))->firstOrFail();
        if ($request->get('delete')) {
            $blog->delete();

            return redirect()->route('admin.blogs')->with('flashSuccess', 'Blog is now updated');
        }
        $blog->title = $request->get('title');
        $blog->description = $request->get('description');
        $slug = @str_slug($request->get('title'));
        if (!$slug) {
            $slug = str_random(7);
        }
        $blog->slug = $slug;
        $blog->save();

        return redirect()->back()->with('flashSuccess', 'Blog is now updated');
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        $blogs = Blog::all();
        $datatables = app('datatables')->of($blogs);

        $datatables->addColumn('actions', function ($image) {
            return '<a href="' . route('admin.blogs.edit', [$image->id]) . '" class="btn btn-info" target="_blank"><i class="fa fa-edit"></i> Edit </a>
                <a href="' . route('blog', [$image->id, $image->slug]) . '" class="btn btn-success" target="_blank"><i class="fa fa-search"></i> View</a>';
        });

        return $datatables->make(true);
    }
}
