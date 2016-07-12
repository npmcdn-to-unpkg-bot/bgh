<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

namespace App\Http\Controllers;

use App\Helpers\ResizeHelper;
use App\Repository\FollowRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\UsersRepositoryInterface;
use App\Http\Requests\User\UpdateAvatar;
use App\Http\Requests\User\UpdatePassword;
use App\Http\Requests\User\UpdateProfile;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(UsersRepositoryInterface $user, ProductRepositoryInterface $products, FollowRepositoryInterface $follow)
    {
        $this->user = $user;
        $this->products = $products;
        $this->follow = $follow;
    }

    public function getUser($user)
    {
        $user = $this->user->getByUsername($user);
        $products = $this->user->getUsersProducts($user);
        $mostUsedTags = mostTags($products->lists('tags'));
        $title = ucfirst($user->fullname);

        return view('user.index', compact('user', 'title', 'products', 'mostUsedTags'));
    }

    public function getFavorites($user)
    {
        $user = $this->user->getByUsername($user);
        $products = $this->user->getUsersFavorites($user);
        $mostUsedTags = mostTags($user->products()->lists('tags'));
        $title = $user->fullname;

        return view('user.favorites', compact('user', 'products', 'title', 'mostUsedTags'));

    }

    public function getFollowers($username)
    {
        $user = $this->user->getUsersFollowers($username);
        $mostUsedTags = mostTags($user->products()->lists('tags'));
        $title = $user->fullname;

        return view('user.followers', compact('user', 'title', 'mostUsedTags'));
    }

    public function getFollowing($username)
    {
        $user = $this->user->getUsersFollowing($username);
        if ($user->id != auth()->user()->id) {
            return redirect()->route('home');
        }
        $mostUsedTags = mostTags($user->products()->lists('tags'));
        $title = $user->fullname;

        return view('user.following', compact('user', 'title', 'mostUsedTags'));
    }

    public function getRss($user)
    {
        return $this->user->getUsersRss($user);
    }

    public function getAll()
    {
        $users = $this->user->getTrendingUsers();
        $title = t('Users');

        return view('user.users', compact('users', 'title'));
    }

    public function getNotifications()
    {
        $notifications = $this->user->notifications(auth()->user()->id);
        $title = t('Notifications');

        return view('user.notifications', compact('notifications', 'title'));
    }

    public function getFeeds()
    {
        $products = $this->user->getFeedForUser();
        $title = t('Feeds');

        return view('gallery/index', compact('products', 'title'));
    }

    public function follow(Request $request)
    {
        return $this->follow->follow($request->get('id'));
    }

    public function getSettings()
    {
        $user = auth()->user();
        $title = t('Settings');

        return view('user.settings', compact('user', 'title'));
    }

    public function postUpdateAvatar(UpdateAvatar $request)
    {
        $i = new ResizeHelper(auth()->user()->avatar, 'uploads/avatars');
        $i->delete();

        $i = new ResizeHelper($request->file('avatar'), 'uploads/avatars');
        list($name, $type) = $i->saveOriginal();

        $update = auth()->user();
        $update->avatar = sprintf('%s.%s', $name, $type);
        $update->save();

        return redirect()->back()->with('flashSuccess', t('Your avatar is now updated'));
    }


    public function postUpdateProfile(UpdateProfile $request)
    {
        $this->user->updateProfile($request);

        return redirect()->back()->with('flashSuccess', t('Your profile is updated'));
    }

    public function postChangePassword(UpdatePassword $request)
    {
        if ( ! $this->user->updatePassword($request)) {
            return redirect()->back()->with('flashError', t('Old password is not valid'));
        }

        return redirect()->back()->with('flashSuccess', t('Your password is updated'));
    }

    public function postMailSettings(Request $request)
    {
        $this->user->updateMail($request);

        return redirect()->back()->with('flashSuccess', t('Your mail settings are now update'));
    }
}
