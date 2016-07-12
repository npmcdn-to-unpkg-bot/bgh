@extends('master/index')
@section('content')
   <h3 class="content-heading">{{ t('Notifications') }}</h3>
   @foreach($notifications as $notice)
      @if($notice->user)
         <div class="media">
            <a class="pull-left" href="#">
               <img class="media-object" alt="{{ $notice->user->fullname }}" src="{{ Resize::avatar($notice->user, 'avatar') }}">
            </a>
            <div class="media-body">
               <h4 class="media-heading black"><a href="{{ route('user', ['username' => $notice->user->username]) }}">{{ $notice->user->fullname }}</a>
         <span class="msg-time pull-right">
         <small><i class="glyphicon glyphicon-time"></i>&nbsp;<abbr class="timeago comment-time" title="{{ $notice->created_at->toISO8601String() }}">{{ $notice->created_at->toISO8601String() }}</abbr>&nbsp;</small>
         </span>
               </h4>
               @if($notice->type == 'follow')
                  <p>Started Following you</p>
               @elseif($notice->type == 'comment' AND $notice->image)
                  <p>Commented on your image <a href="{{ route('image', ['id' => $notice->image->id, 'slug' => $notice->image->slug]) }}">{{ ucfirst($notice->image->title) }}</a></p>
               @elseif($notice->type == 'like' AND $notice->image)
                  <p>Liked your image <a href="{{ route('image', ['id' => $notice->image->id, 'slug' => $notice->image->slug]) }}">{{ ucfirst($notice->image->title) }}</a></p>
               @elseif($notice->type == 'reply' AND $notice->image)
                  <p>Replied on your comment <a href="{{ route('image', ['id' => $notice->image->id, 'slug' => $notice->image->slug]) }}">{{ ucfirst($notice->image->title) }}</a></p>
               @elseif($notice->type == 'follow' AND $notice->image)
                  <p>Started Following Your</p>
               @endif
            </div>
         </div>
         <hr>
      @endif
   @endforeach
@endsection
@section('pagination')
    <div class="container">
        {!! $notifications->render()  !!}
    </div>
@endsection