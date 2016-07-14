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

    public function getById($id);

    public function getByUsername($username);

    public function getAllUsers();

    public function getTrendingUsers();

    public function getUsersFollowers($username);

    public function getUsersFollowing($username);

    public function getUsersProducts(User $user);

    public function createNew($request);

    public function notifications($id);

    public function activate($username, $activationCode);

    public function registerViaSocial($request);

    public function updateProfile(Request $input);

    public function updateMail(Request $input);

    public function updatePassword(Request $input);

    public function getFeedForUser();

}
