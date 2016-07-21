<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

use App\Repository\ProfileRepositoryInterface;
use App\Repository\UsersRepositoryInterface;

class ProfileController extends Controller
{

    public function __construct(ProfileRepositoryInterface $profile, UsersRepositoryInterface $user)
    {
        $this->user = $user;
        $this->profile = $profile;
    }


    public function getList()
    {
        $title = t('Profiles');

        // $profiles = Profile::all();

        return view('admin.profile.list', compact('title'));
    }

    public function getData(Request $request)
    {
        $profiles = Profile::all();

        $datatables = app('datatables')->of($profiles);

        $datatables->addColumn('actions', function ($profile) {
            return '<a href="' . route('admin.profiles.edit', [$profile->id]) . '" class="btn btn-sm btn-default"><i class="fa fa-edit"></i> Edit </a>';
        });

        return $datatables->editColumn('created_at', '{!! $created_at->diffForHumans() !!}')
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->make(true);
    }


    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',
        ]);

        $profile = new Profile();
        $profile->name = $request->get('name');
        $profile->title = $request->get('title');
        $profile->save();

        Artisan::call('cache:clear');
        return redirect()->back()->with('flashSuccess', 'New Profile Is Added');
    }


    public function edit($id)
    {
       $title = t('Edit');

        $profile = Profile::where('id', '=', $id)->first();

        $field_users = [];
        foreach (User::all() as $p) {

            $arr['id'] = $p->id;
            $arr['fullname'] = $p->fullname;

            if($profile->users->contains($p->id)){
                $arr['value'] = true;
            }
            else{
                $arr['value'] = false;
            }

            array_push($field_users, $arr);
        }

        return view('admin.profile.edit', compact('title','profile','field_users'));

    }

    public function patch($id, Request $request)
    {
        $this->validate($request, [
            'title'   => ['required'],
            // 'slug' => ['required', 'alpha_dash'],
            'name' => ['required']
        ]);

        $profile = Profile::findOrFail($id);

        $delete = $request->get('delete');
        if ($delete) {
            // if ($request->get('shiftprofile')) {
            //     foreach ($profile->images as $image) {
            //         $image->profile_id = $request->get('shiftprofile');
            //         $image->save();
            //     }
            // }
            $profile->delete();

            return redirect()->back()->with('flashSuccess', 'Profile is now deleted');
        }

        $users = (array) $request->get('users');
        $profile->users()->sync($users);

        $profile->name = $request->get('name');
        $profile->title = $request->get('title');

        $profile->save();

        Artisan::call('cache:clear');

        return redirect()->back()->with('flashSuccess', 'User profile is now updated');
    }



}
