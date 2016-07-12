@extends('admin.master.index')
@section('content')
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('image',['id' => $comment->image->id, 'slug' => $comment->image->slug]) }}" target="_blank"><img src="{{ Resize::image($comment->image,'gallery') }}" class="thumbnail img-responsive"></a>
            <ul class="list-group">
                <a href="#" class="list-group-item disabled">
                    Comments Statics
                </a>
                <li class="list-group-item"><strong>Created By</strong> <a href="{{ route('user', [$comment->user->username]) }}">{{ $comment->user->fullname }}</a></li>
                <li class="list-group-item"><strong>Replies</strong> {{ $comment->replies->count() }}</li>
                <li class="list-group-item"><strong>Votes</strong> {{ $comment->votes->count() }}</li>
                <li class="list-group-item"><strong>Created At</strong> {{ $comment->created_at->diffForHumans() }} </li>
                <li class="list-group-item"><strong>Last Updated</strong> {{ $comment->updated_at->diffForHumans() }} </li>
            </ul>
        </div>
        <div class="col-md-9">
            {!! Form::open() !!}

            <div class="form-group">
                {!! Form::label('comment', 'Comment') !!}
                {!! Form::textarea('comment', $comment->comment, ['class' => 'form-control input-lg', 'placeholder' => 'Description']) !!}
            </div>

            <div class="form-group">
                <label for="featured_at">Delete</label>
                {!! Form::checkbox('delete', 1) !!}
            </div>

            {!! Form::submit('Update', ['class' => 'btn btn-success btn-lg']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('extra-js')
    <script>
        $(function(){
            $(".tagging").select2({
                theme: "bootstrap",
                minimumInputLength: 3,
                maximumSelectionLength: {{ (int)siteSettings('tagsLimit') }},
                tags: true,
                tokenSeparators: [","]
            })
        });
    </script>
@endsection