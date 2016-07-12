@extends('master/index')
@section('custom')
    @include('user/rightsidebar')
    <div class="col-md-9">
        <style>
            .following {
                margin-bottom: 10px;
            }
        </style>
        <h3 class="content-heading">{{ t('Users')}} I'm following</h3>

        @foreach($user->following as $follower)
            <div class="col-md-4 br-right following clearfix">
                <a href="{{ route('user', ['username' => $follower->followingUser->username]) }}" class="pull-left user-profile-avatar">
                    <img src="{{ Resize::avatar($follower,'avatar') }}" alt="{{ $follower->followingUser->username }}">
                </a>
                <h4>{{ $follower->followingUser->fullname }}<br>
                    <small>{{ $follower->followingUser->username }}</small>
                </h4>
                @if(auth()->check() == true)
                    @if(checkFollow($follower->followingUser->id))
                        <a type="button" class="btn btn-info btn-xs  follow" id="{{ $follower->followingUser->id }}">{{ t('Un Follow') }}</a>
                    @else
                        <a type="button" class="btn btn-info btn-xs  follow" id="{{ $follower->followingUser->id }}">{{ t('Follow Me') }}</a>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
@endsection
@section('sidebar')
@endsection