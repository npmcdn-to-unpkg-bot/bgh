<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository;

use Auth;

interface CommentsRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function create($request);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $request
     * @return mixed
     */
    public function vote($request);
}
