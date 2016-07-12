@extends('master/index')

@section('content')
    <h1 class="content-heading">{{ t('Editing Product') }}</h1>
    {!! Form::open() !!}
    <div class="form-group">
        {!! Form::label('title', t('Title')) !!}
        {!!  Form::text('title',$product->title,['class'=>'form-control input-lg','required'=>'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('description', t('Description')) !!}
        {!! Form::textarea('description',$product->description,['class'=>'form-control input-lg'])  !!}
    </div>
    <div class="form-group form-group-lg">
        {!! Form::label('tags', 'Tags') !!}
        <select class="form-control input-lg tagging" multiple="multiple" name="tags[]">
            @foreach(explode(',',$product->tags) as $tag)
                 @if($tag)
                    <option selected="selected">{{ $tag }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group">
        {!! Form::submit('Update Product', ['class'=>'btn btn-success btn-lg'])  !!}
    </div>
    {!! Form::close()  !!}
@endsection

@section('extrafooter')
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

@section('sidebar')
    @include('product/sidebar')
@endsection