<div class="comments-block">
    <h3 class="content-heading">{{ t('Comments') }}</h3>
    {!! Form::open() !!}
    <div class="form-group">
        {!! Form::textarea('comment', null,['class'=>"form-control",'rows'=>2,'placeholder'=>t('Comment')]) !!}
    </div>
    <div class="form-group">
        {!! Form::submit(t('Comment'),['class'=>'btn btn-info']) !!}
    </div>
    {!! Form::close() !!}

    @foreach($comments as $comment)
        <div class="media" id="comment-{{ $comment->id }}">
            <a class="pull-left" href="{{ route('user', ['user' => $comment->user->username]) }}">
                <img class="media-object" src="{{ Resize::img($comment->user->avatar,'avatar') }}" alt="{{ $comment->user->fullname }}">
            </a>

            <div class="media-body">
                <h4 class="media-heading"><a href="{{ route('user', ['user' => $comment->user->username]) }}">{{ ucfirst($comment->user->fullname) }}</a> <span class="pull-right">
                @if(auth()->check() == true AND ($comment->user_id == auth()->user()->id || $product->user->id == auth()->user()->id))
                            <button data-content="{{ $comment->id }}" type="button" class="close delete-comment" aria-hidden="true">&times;</button>
                        @endif
                        <i class="comment-time fa fa-clock-o"></i> <abbr class="timeago comment-time" title="{{ $comment->created_at->toISO8601String() }}">{{ $comment->created_at->toISO8601String() }}</abbr>
                </span></h4>
                <p>{!! \App\Helpers\Smilies::parse($comment->comment)  !!}</p>
                <span class="comment-vote"><span id="data-comment-{{ $comment->id }}">{{ $comment->votes->count() }}</span> <a class="fa fa-chevron-circle-up fa-fw {{ checkVoted($comment->votes) == true ? 'comment-voted':'' }}  {{ auth()->check() == true ? 'vote-comment':'' }}" data-id="{{ $comment->id }}"></a></span>
                @if(auth()->check())
                    <a class="replybutton" id="box-{{ $comment->id }}">{{ t('Reply') }}</a>
                    <div class="commentReplyBox" id="openbox-{{ $comment->id }}">
                        <div class="form-group">
                            <input type="hidden" name="pid" value="19">
                            {!! Form::textarea('comment', null ,['id'=>'textboxcontent'.$comment->id,'class'=>"form-control",'rows'=>2,'placeholder'=>t('Comment')]) !!}
                        </div>
                        <div class="form-group">
                            <button class="btn btn-info replyMainButton" id="{{ $comment->id }}">{{ t('Reply') }}</button>
                            <a class="closebutton" id="box-{{ $comment->id }}">{{ t('Cancel') }}</a>
                        </div>
                    </div>
                    <span class="reply-add-{{ $comment->id }}"></span>
                @endif
                @foreach($comment->replies as $reply)
                    <hr/>
                    <div class="media" id="reply-{{ $reply->id }}">
                        <a class="pull-left" href="{{ route('user', ['user' => $reply->user->username]) }}">
                            <img class="media-object" src="{{ Resize::img($reply->user->avatar, 'avatar') }}" alt="{{ $reply->user->fullname }}">
                        </a>

                        <div class="media-body">
                            <h4 class="media-heading"><a href="{{ route('user', ['user' => $reply->user->username]) }}">{{ ucfirst($reply->user->fullname) }}</a> <span class="pull-right">
                                    @if(auth()->check() === true AND ($reply->user_id == auth()->user()->id || $product->id == auth()->user()->id || $reply->comment->user->id == auth()->user()->id))
                                        <span class="right"><button data-content="{{ $reply->id }}" type="button" class="close delete-reply" aria-hidden="true">&times;</button></span>
                                    @endif
                                    <i class="comment-time fa fa-clock-o"></i> <abbr class="timeago comment-time" title="{{ $reply->created_at->toISO8601String() }}">{{ $reply->created_at->toISO8601String() }}</abbr> </span></h4>
                            <p>{!! \App\Helpers\Smilies::parse($reply->reply) !!}</p>

                            <p><span class="comment-vote"><span id="data-reply-{{ $reply->id }}">{{ $reply->votes->count() }}</span> <a class="fa fa-chevron-circle-up fa-fw {{ checkVoted($reply->votes) == true ? 'comment-voted':'' }} {{ auth()->check() == true ? 'vote-reply':'' }}" data-id="{{ $reply->id }}"></a></span></p>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr/>
        </div>
    @endforeach
    <div class="row">
        {!! $comments->render() !!}
    </div>
</div>
