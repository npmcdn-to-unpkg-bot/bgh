<?php

namespace App\Http\Controllers\Admin\User;

use App\Helpers\ResizeHelper;

use App\Models\Notification;
use App\Models\User;
use App\Models\Profile;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Repository\ProfileRepositoryInterface;
use App\Repository\UsersRepositoryInterface;


class UpdateController extends Controller
{

    public function __construct(ProfileRepositoryInterface $profile, UsersRepositoryInterface $user)
    {
        $this->user = $user;
        $this->profile = $profile;
    }


    public function getEdit($id)
    {
        $user = User::whereId($id)->with('products', 'favorites')->firstOrFail();
        $title = t('Edit');

        $field_profiles = [];
        foreach (Profile::all() as $p) {

            $arr['id'] = $p->id;
            $arr['title'] = $p->title;

            if($user->profiles->contains($p->id)){
                $arr['value'] = true;
            }
            else{
                $arr['value'] = false;
            }

            array_push($field_profiles, $arr);
        }

        return view('admin.user.edit', compact('user', 'title','field_profiles'));
    }

    public function postAddUser(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'email'    => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->confirmed_at = Carbon::now();
        $user->save();

        return redirect()->back()->with('flashSuccess', 'User is not added');
    }

    public function postEdit(Request $request)
    {
        $this->validate($request, [
            'fullname'   => 'required',
            'email'      => 'required|email',
            'blog_url'   => 'url',
            'fb_link'    => 'url',
            'tw_link'    => 'url',
            'permission' => 'required',
            'country'    => 'max:3',
            'delete'     => 'boolean',
        ]);

        $user = User::whereId($request->route('id'))->firstOrFail();

        if ($request->get('delete')) {
            // foreach ($user->products()->get() as $product) {
            //     $product->favorites()->delete();
            //     foreach ($product->comments()->get() as $comment) {
            //         $comment->votes()->delete();
            //         foreach ($comment->reply()->get() as $reply) {
            //             $reply->votes()->delete();
            //             $reply->delete();
            //         }
            //         $comment->delete();
            //     }
            //     $d = new ResizeHelper($product->main_image, 'uploads/products');
            //     $d->delete();
            //     $product->info()->delete();
            //     $product->delete();
            // }
            // foreach ($user->comments()->get() as $comment) {
            //     $comment->votes()->delete();
            //     foreach ($comment->reply()->get() as $reply) {
            //         $reply->votes()->delete();
            //         $reply->delete();
            //     }
            //     $comment->delete();
            // }
            //
            Notification::whereFromId($user->id)->delete();
            Notification::whereUserId($user->id)->delete();

            $user->favorites()->delete();
            $user->followers()->delete();
            $user->following()->delete();
            $user->delete();

            return redirect()->route('admin.users')->with('flashSuccess', 'User deleted');
        }

        $user->fullname = $request->get('fullname');
        $user->email = $request->get('email');
        $user->about_me = $request->get('about_me');
        $user->blogurl = $request->get('blog_url');
        $user->fb_link = $request->get('fb_link');
        $user->tw_link = $request->get('tw_link');
        $user->permission = $request->get('permission');

        $profiles = (array) $request->get('profiles');
        $user->profiles()->sync($profiles);

        if ($request->get('country') == 'null') {
            $user->country = null;
        } else {
            $user->country = $request->get('country');
        }

        $user->save();

        return redirect()->back()->with('flashSuccess', 'User updated');
    }

    public function postApprove(Request $request)
    {
        $user = User::whereId($request->get('id'))->firstOrFail();
        if ($request->get('approve') == 1) {
            $user->confirmed_at = Carbon::now();
            $user->save();
            return 'Approved';
        }
        if ($request->get('approve') == 0) {
            $user->delete();
            return 'Deleted';
        }

    }
}
