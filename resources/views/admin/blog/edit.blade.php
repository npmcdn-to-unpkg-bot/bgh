@extends('admin/master/index')
@section('content')
    <div class="box">
        <div class="box-body">
            {!! Form::open()  !!}
            <div class="form-group">
                {!! Form::label('title') !!}
                {!! Form::text('title',$blog->title,['class'=>'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('description') !!}
                {!! Form::textarea('description',$blog->description,array('class'=>'form-control ckeditor'))  !!}
            </div>

            <div class="form-group">
                <label for="featured">Delete this blog </label>
                {!! Form::checkbox('delete','TRUE', false)  !!}
                ( <i class="fa fa-info" data-toggle="tooltip" data-placement="top" data-original-title="Deleting this blog, it can't restored"></i> )
            </div>
            <div class="form-group">
                {!! Form::submit('Update',['class'=>'btn btn-success'])  !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('extra-js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.2/ckeditor.js"></script>
@endsection