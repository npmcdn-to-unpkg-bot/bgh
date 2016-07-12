@extends('admin.master.index')
@section('content')
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('product',['id' => $product->id, 'slug' => $product->slug]) }}" target="_blank"><img src="{{ Resize::img($product->main_image,'featuredProduct') }}" class="thumbnail img-responsive"></a>
            <div class="form-group">
                <button class="btn btn-danger clearProductCache" data-product="{{ $product->id }}"><i class="ion ion-nuclear"></i> Clear Cache and Thumbnails</button>
            </div>
            <ul class="list-group">
                <a href="#" class="list-group-item disabled">
                    Statics
                </a>
                <li class="list-group-item"><strong>Uploader</strong> <a href="{{ route('user', [$product->user->username]) }}">{{ $product->user->fullname }}</a></li>
                <li class="list-group-item"><strong>Views</strong> {{ $product->views }}</li>
                <li class="list-group-item"><strong>Downloads</strong>  {{ $product->downloads }}</li>
                <li class="list-group-item"><strong>Uploaded At</strong> {{ $product->created_at->diffForHumans() }} </li>
                <li class="list-group-item"><strong>Last Updated</strong> {{ $product->updated_at->diffForHumans() }} </li>
                <li class="list-group-item"><strong>Featured At</strong> {{ $product->featured_at  == null ? 'Not Featured' : $product->featured_at->diffForHumans() }} </li>
            </ul>
        </div>
        <div class="col-md-9">
            {!! Form::open(array('files' => true)) !!}
            <div class="form-group">
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', $product->title, ['class' => 'form-control input-lg', 'placeholder' => 'Title of Product']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('slug', 'Slug') !!}
                {!! Form::text('slug', $product->slug, ['class' => 'form-control input-lg', 'placeholder' => 'Slug']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('description', 'Description') !!}
                {!! Form::textarea('description', $product->description, ['class' => 'form-control input-lg', 'placeholder' => 'Description']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('cover', 'cover') !!}
                {!! Form::file('cover_image') !!}
                <img src="{{ Resize::img($product->info->cover_image,'coverProduct') }}"  width="300"/>
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

            <div class="form-group form-group-lg">
                {!! Form::label('categories', 'Categories') !!}
                <ul id="categories" class="tree">
                    <?php

                    $curDepth = 0;
                    $counter = 0;
                    foreach ($categories as $category){

                        if ($category->depth == $curDepth){
                            if ($counter > 0) echo "</li>";
                        }
                        elseif ($category->depth > $curDepth){
                            echo "<ul>";
                            $curDepth = $category->depth;
                        }
                        elseif ($category->depth < $curDepth){
                            echo str_repeat("</li></ul>", $curDepth - $category->depth), "</li>";
                            $curDepth = $category->depth;
                        }

                        ?>
                        <li>
                            <label><input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ $category->checked }}/>{{ $category->name }}</label>

                        <?php

                        $counter++;
                    }

                    echo str_repeat("</li></ol>", $curDepth), "</li>";

                    ?>
                </ul>

            </div>

            <div class="form-group">
                {!! Form::label('featured_at', 'Is Featured Product') !!}
                {!! Form::checkbox('featured_at', 1, (bool)$product->featured_at) !!}
            </div>
            <div class="form-group">
                {!! Form::label('delete', 'Delete this product') !!}
                {!! Form::checkbox('delete', 1) !!}
            </div>
            {!! Form::submit('Update', ['class' => 'btn btn-success btn-lg']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('extra-js')

    {!! HTML::script('static/admin/js/jquery-checktree.js') !!}

    <script>





        $(document).ready(function() {

            // $('ul#categories').checktree();

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