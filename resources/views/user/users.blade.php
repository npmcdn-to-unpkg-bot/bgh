@extends('master/index')
@section('content')
    <h3 class="content-heading">{{ t('Users')}} </h3>
    @foreach($users as $user)
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4 col-sm-3 pull-left">
                    <a href="{{ route('user', ['username' => $user->username]) }}"><img class="thumbnail img-responsive" src="{{ Resize::avatar($user, 'listingAvatar') }}"></a>
                </div>
                <div class="col-md-8">
                    <h3 style="margin-top:0px">
                        <a href="{{ route('user', ['username' => $user->username]) }}">{{ ucfirst($user->fullname) }}</a>

                        <p>
                            <small><i class="glyphicon glyphicon-comment"></i> {{ $user->comments->count() }} {{ t('comments') }} &middot; <i class="glyphicon glyphicon-picture"></i> {{ $user->images->count() }} {{ t('images') }}</small>
                        </p>
                    </h3>
                    <p>{{ str_limit($user->about_me,50) }}</p>
                </div>
            </div>
            @foreach($user->latestImages->take(3) as $image)
                <div class="col-md-2 col-sm-3 col-xs-3">
                    <a href="{{ route('image', ['id' => $image->id, 'slug' => $image->slug]) }}"><img src="{{ Resize::image($image, 'listingImage') }}" class="thumbnail img-responsive"></a>
                </div>
            @endforeach
            <div class="clearfix"></div>
            <hr/>
        </div>
    @endforeach
@endsection
@section('pagination')
    <div class="container">
        {!! $users->appends(Input::except('page'))->render() !!}
    </div>
@endsection