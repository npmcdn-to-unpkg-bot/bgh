<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository;

use Auth;
use Carbon;
use Feed;
use Hash;
use Illuminate\Http\Request;
use URL;
use App\Models\User;

interface UsersRepositoryInterface
{

    /**
     * @param $id
     * @return bool
     */
    public function getById($id);

    /**
     * @param $username
     * @return bool
     */
    public function getByUsername($username);

    /**
     * @return mixed
     */
    public function getAllUsers();

    /**
     * @return mixed
     */
    public function getTrendingUsers();

    /**
     * @param User $user
     * @return mixed
     */
    public function getUsersFavorites(User $user);

    /**
     * @param $username
     * @return mixed
     */
    public function getUsersFollowers($username);

    /**
     * @param $username
     * @return mixed
     */
    public function getUsersFollowing($username);

    /**
     * @param User $user
     * @return mixed
     */
    public function getUsersImages(User $user);

    /**
     * @param array $input
     * @return bool
     */
    public function createNew($request);

    /**
     * @param $id
     * @return mixed
     */
    public function notifications($id);

    /**
     * @param $username
     * @param $activationCode
     * @return bool
     */
    public function activate($username, $activationCode);

    /**
     * @param $request
     * @return mixed
     */
    public function registerViaSocial($request);

    /**
     * @param $input
     * @return mixed
     */
    public function updateProfile(Request $input);

    /**
     * @param $input
     * @return bool
     */
    public function updateMail(Request $input);

    /**
     * @param $input
     * @return bool
     */
    public function updatePassword(Request $input);

    /**
     * @return mixed
     */
    public function getFeedForUser();

    /**
     * @param $username
     * @return mixed
     */
    public function getUsersRss($username);
}
