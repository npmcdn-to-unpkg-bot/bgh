<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository;

use Auth;
use DB;
use File;
use Request;

interface FollowRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function follow($id);
}
