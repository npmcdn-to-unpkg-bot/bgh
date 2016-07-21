<div class="col-md-3">
    <a href="{{ route('user', ['username' => $user->username]) }}" class="thumbnail">
        <img src="{{ Resize::img($user->avatar,'mainAvatar') }}" alt="{{ $user->username }}">
    </a>

    <h1 class="profile-fullname">
        <span>{{ $user->fullname }}</span>

        <p>
            <small>{{ $user->username }}</small>
        </p>
    </h1>
    <hr>
    <h2 class="profile-social">
        @if(strlen($user->fb_link) > 2)
            <a href="{{ addhttp($user->fb_link) }}" class="black entypo-facebook" target="_blank"></a>
        @endif
        @if(strlen($user->tw_link) > 2)
            <a href="{{ addhttp($user->tw_link) }}" class="black entypo-twitter" target="_blank"></a>
        @endif
        @if(strlen($user->blogurl) > 2)
            <a href="{{ addhttp($user->blogurl) }}" class="black fa fa-link" target="_blank"></a>
        @endif
    </h2>
    <hr>
    @if(auth()->check() == true)
        @if(auth()->user()->id == $user->id)
            <span>sos este usuario</span>
            <a href="{{ route('users.settings') }}" type="button" class="btn btn-info btn-lg btn-block">{{ t('Edit My Profile') }}</a>
        @else
            <span>no sos este usuario</span>
        @endif
        <hr>
    @endif

    <div class="userdetails">

        <h3 class="content-heading">{{ t('Status') }}</h3>

        <p><i class="fa fa-eye"></i> {{ $user->products()->sum('views') }} {{ t('Views') }}</p>

        <p><i class="fa fa-picture-o"></i> {{ $user->products()->count() }} {{ t('Products Shared') }}</p>

        <h3 class="content-heading">{{ t('Most Used Tags') }}</h3>
        @foreach($mostUsedTags as $tag => $key)
            <a href="{{ route('tags', $key) }}" class="tag"><span class="label label-info">{{ $key }}</span></a>
        @endforeach
        <hr>

        @if(strlen($user->about_me) > 2)
            <h3 class="content-heading">{{ t('About Me') }}</h3>

            <p>{{ $user->about_me }}</p>
            <hr>
        @endif

        @if(strlen($user->country) == 2)
            <h3 class="content-heading">{{ t('Country') }}</h3>

            <p>{{ countryResolver($user->country) }}</p>
            <hr>
        @endif

    </div>


    @if(auth()->check() and auth()->user()->id != $user->id)
        <small><a href="{{ route('user.report',['username' => $user->username]) }}">{{ t('Report') }}</a></small>
    @endif
</div>