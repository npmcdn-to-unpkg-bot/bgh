<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository\Eloquent;

use App\Models\Blog;
use App\Repository\BlogRepositoryInterface;

class BlogRepository implements BlogRepositoryInterface
{
    /**
     * @param Blog $blogs
     */
    public function __construct(Blog $blogs)
    {
        $this->blogs = $blogs;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->blogs->whereId($id)->with('user')->firstOrFail();
    }

    /**
     * @param null $paginate
     * @return mixed
     */
    public function getLatestBlogs($paginate = null)
    {
        $blogs = $this->blogs->orderBy('created_at', 'desc')->with('user');

        return $blogs->paginate(perPage());
    }
}
