<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository;

use Auth;

interface ReplyRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $request
     * @return mixed
     */
    public function createReply($request);

    /**
     * @param $input
     * @return mixed
     */
    public function delete($input);

    /**
     * @param $replyId
     * @return mixed
     */
    public function vote($replyId);

}
