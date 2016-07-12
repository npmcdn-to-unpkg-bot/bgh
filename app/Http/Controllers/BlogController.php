<?php
namespace App\Http\Controllers;

use App\Repository\BlogRepositoryInterface;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

class BlogController extends Controller
{
    /**
     * @param BlogRepositoryInterface $blogs
     */
    public function __construct(BlogRepositoryInterface $blogs)
    {
        $this->blogs = $blogs;
    }

    /**
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function getIndex()
    {
        $blogs = $this->blogs->getLatestBlogs(5);
        $title = t('All Blogs');

        return view('blog.list', compact('title', 'blogs'));
    }

    /**
     * @param $id
     * @param $slug
     * @return \BladeView|bool|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getBlog($id, $slug)
    {
        $blog = $this->blogs->getById($id);

        if (!$blog) {
            return redirect()->route('home');
        }

        if (empty($slug) || $slug != $blog->slug) {
            return redirect()->route('blog', ['id' => $blog->id, 'slug' => $blog->slug]);
        }

        $title = $blog->title;

        return view('blog.blog', compact('title', 'blog'));
    }
}
