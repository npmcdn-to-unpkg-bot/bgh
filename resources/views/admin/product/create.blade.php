@extends('admin.master.index')
@section('content')
    <div class="row">
        <div class="col-md-3">
            <a href="#" target="_blank"><img src="" class="thumbnail img-responsive"></a>
        </div>
        <div class="col-md-9">
            {!! Form::open() !!}
            <div class="form-group">
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', null, ['class' => 'form-control input-lg', 'placeholder' => 'Title of Product']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('slug', 'Slug') !!}
                {!! Form::text('slug', null, ['class' => 'form-control input-lg', 'placeholder' => 'Slug']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('description', 'Description') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control input-lg', 'placeholder' => 'Description']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('category', t('Category')) !!}
                <select name="category" class="form-control input-lg" required>
                    @foreach(productCategories() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group form-group-lg">
                {!! Form::label('tags', 'Tags') !!}
                <select class="form-control input-lg tagging" multiple="multiple" name="tags[]">
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
                {!! Form::checkbox('featured_at', 1, null) !!}
            </div>

            {!! Form::submit('Create', ['class' => 'btn btn-success btn-lg']) !!}
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