<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository;

interface BlogRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param null $paginate
     * @return mixed
     */
    public function getLatestBlogs($paginate = null);
}
