<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository\Eloquent;

use App\Helpers\Resize;
use App\Mailers\UserMailer;
use App\Models\Product;
use App\Models\User;
use App\Repository\UsersRepositoryInterface;
use Carbon\Carbon;
use Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class UsersRepository extends AbstractRepository implements UsersRepositoryInterface
{

    public function __construct(UserMailer $mailer, User $user, Product $products)
    {
        $this->model = $user;
        $this->mailer = $mailer;
        $this->products = $products;
    }

    public function getById($id)
    {
        $user = $this->model->whereId($id)->firstOrFail();

        return $user;
    }

    public function getByUsername($username)
    {
        $user = $this->model->where('username', $username)->with('followers.user')->firstOrFail();

        return $user;
    }

    public function getAllUsers()
    {
        return $this->model->confirmed()->with('latestProducts')->paginate(perPage());
    }

    public function getTrendingUsers()
    {
        return $this->model->confirmed()
            ->leftJoin('products', 'products.user_id', '=', 'users.id')
            ->select('users.*')
            ->groupBy('users.id')->with('products', 'latestProducts')->orderBy('popular', 'desc')
            ->paginate(perPage());
    }


    public function getUsersFollowers($username)
    {
        return $this->model->whereUsername($username)->with('followers')->first();
    }

    public function getUsersFollowing($username)
    {
        return $this->model->whereUsername($username)->with('following.followingUser')->first();
    }

    public function getUsersProducts(User $user)
    {
        return $this->products->approved()->whereUserId($user->id)->with('user')->orderBy('approved_at', 'desc')->paginate(perPage());
    }

    public function createNew($request)
    {
        $activationCode = sha1(str_random(11) . (time() * rand(2, 2000)));

        $this->model->username = $request->get('username');
        $this->model->fullname = $request->get('fullname');
        $this->model->gender = $request->get('gender');
        $this->model->email = $request->get('email');
        $this->model->password = bcrypt($request->get('password'));
        $this->model->email_confirmation = $activationCode;
        $this->model->save();

        $this->mailer->activation($this->model, $activationCode);

        return true;
    }

    public function notifications($id)
    {
        $user = $this->model->whereId($id)->with('notifications')->first();
        $notices = $user->notifications()->orderBy('created_at', 'desc')->paginate(perPage());
        foreach ($notices as $notice) {
            if (!$notice->is_read) {
                $notice->is_read = 1;
                $notice->save();
            }
        }

        return $notices;
    }

    public function activate($username, $activationCode)
    {
        $user = $this->model->whereUsername($username)->first();
        if ($user && $user->email_confirmation === $activationCode) {
            $user->confirmed_at = Carbon::now();
            $user->save();

            return $user;
        }

        return false;
    }

    public function registerViaSocial($request)
    {
        $this->model->username = $request->get('username');
        $this->model->password = bcrypt($request->get('password'));
        $this->model->fullname = $request->session()->get($request->route('provider') . '_register')->getName();
        if (isset($request->session()->get($request->route('provider') . '_register')->user['gender'])) {
            $this->model->gender = strtolower($request->session()->get($request->route('provider') . '_register')->user['gender']);
        }
        if ($request->session()->has('facebook_register')) {
            $this->model->email = $request->session()->get($request->route('provider') . '_register')->getEmail();
            $this->model->fbid = $request->session()->get('facebook_register')->getId();
            Session::forget('facebook_register');
        }
        if ($request->session()->has('google_register')) {
            $this->model->email = $request->session()->get($request->route('provider') . '_register')->getEmail();
            $this->model->gid = $request->session()->get('google_register')->getId();
            Session::forget('google_register');
        }
        if ($request->session()->has('twitter_register') and session('user_email')) {
            $activationCode = sha1(str_random(11) . (time() * rand(2, 2000)));
            $this->model->email = session('user_email');
            $this->model->twid = $request->session()->get('twitter_register')->getId();
            $this->model->email_confirmation = $activationCode;
            $this->model->save();
            $this->mailer->activation($this->model, $activationCode);
            Session::forget('twitter_register');

            return true;
        }
        $this->model->confirmed_at = Carbon::now();
        $this->model->save();
        auth()->loginUsingId($this->model->id);

        return $this->model;
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $user->fullname = $request->get('fullname');
        $user->dob = $request->get('dob');
        $user->gender = $request->get('gender');
        $user->country = $request->get('country');
        $user->about_me = $request->get('about_me') ? $request->get('about_me') : null;
        $user->blogurl = $request->get('blogurl') ? $request->get('blogurl') : null;
        $user->fb_link = $request->get('fb_link') ? $request->get('fb_link') : null;
        $user->tw_link = $request->get('tw_link') ? $request->get('tw_link') : null;
        $user->save();

        return $user;
    }

    public function updateMail(Request $request)
    {
        $user = $request->user();

        $user->email_comment = $request->get('email_comment') ? 1 : 0;
        $user->email_reply = $request->get('email_reply') ? 1 : 0;
        $user->email_favorite = $request->get('email_favorite') ? 1 : 0;
        $user->email_follow = $request->get('email_follow') ? 1 : 0;
        $user->save();

        return true;
    }

    public function updatePassword(Request $request)
    {
        if (Hash::check($request->get('currentpassword'), $request->user()->password)) {
            $user = auth()->user();
            $user->password = bcrypt($request->get('password'));
            $user->save();

            return true;
        }

        return false;
    }

    public function getFeedForUser()
    {
        $following = auth()->user()->following()->lists('follow_id');
        if (!$following) {
            $following = [null];
        }

        return $this->products->whereIn('user_id', $following)->orderBy('approved_at', 'desc')->paginate(perPage());
    }


}
