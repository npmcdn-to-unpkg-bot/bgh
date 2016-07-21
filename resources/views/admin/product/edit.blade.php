@extends('admin.master.index')
@section('content')
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('product',['id' => $product->id, 'slug' => $product->slug]) }}" target="_blank"><img src="{{ Resize::img($product->main_image,'featuredProduct') }}" class="thumbnail img-responsive"></a>
            <div class="form-group">
                {{-- <button type="button" class="btn btn-danger clearProductCache" data-product="{{ $product->id }}"><i class="ion ion-nuclear"></i> Clear Cache</button> --}}
            </div>
            <ul class="list-group">
                <a href="#" class="list-group-item disabled">
                    Statics
                </a>
                <li class="list-group-item"><strong>User</strong> {{ $product->user->fullname }}</li>
                <li class="list-group-item"><strong>Views</strong> {{ $product->views }}</li>
                <li class="list-group-item"><strong>In Categories</strong> {{ $product->categories->count() }}</li>
                <li class="list-group-item"><strong>Uploaded At</strong> {{ $product->created_at->diffForHumans() }} </li>
                <li class="list-group-item"><strong>Last Updated</strong> {{ $product->updated_at->diffForHumans() }} </li>
                <li class="list-group-item"><strong>Featured At</strong> {{ $product->featured_at  == null ? 'Not Featured' : $product->featured_at->diffForHumans() }} </li>
            </ul>
        </div>
        <div class="col-md-9">
        {!! Form::open(['method' => 'PATCH', 'files' => true, 'id' => 'mainForm']) !!}

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
                {!! Form::label('is_microsite', 'Is Microsite Product') !!}
                {!! Form::select('is_microsite',['1' => 'Yes', '0' => 'No'],$product->is_microsite,['class' => 'form-control']) !!}
            </div>

            <div class="form-group form-input-file">
                {!! Form::label('cover', 'cover') !!}
                <div class="form-input-file-hide">
                    {!! Form::file('cover_image') !!}
                </div>
                <img class="form-input-file-image-original" src="{{ Resize::img($product->info->cover_image,'coverProduct') }}"  width="300"/>
                <img class="form-input-file-image-new" src=""  width="300"/>
                <span class="form-input-file-label"></span>
                <button type="button" class="btn btn-default form-input-file-btn-change"><i class="fa fa-folder-open"></i></button>
                <button type="button" class="btn btn-default form-input-file-btn-back"><i class="fa fa-close"></i></button>
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

            {!! Form::submit('Update', ['class' => 'btn btn-success btn-lg']) !!}
            {!! Form::button('Delete', ['class' => 'btn btn-danger btn-lg', 'id' => 'btn_delete']) !!}

            <i class="fa fa-cog fa-spin fa-fw loading fa-2x"></i>
            <div class="progress progress-striped active" style="display:none;">
                <div class="progress-bar progress-bar-success" style="width:0%"></div>
            </div>

        {!! Form::close() !!}
        </div>
    </div>

    {{ Form::open(['method' => 'DELETE', 'route' => ['admin.products.edit', $product->id], 'name' => 'delete']) }}
    {{ Form::close() }}

    {{--
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Header</h4>
          </div>
          <div class="modal-body">
            <p>Some text in the modal.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
     --}}

@endsection

@section('extra-js')

    <style>
        .form-input-file-hide{
            overflow: hidden;
            position: relative;
            cursor: pointer;
            background-color: #DDF;
        }

        .form-input-file-hide input[type="file"]{
            cursor: pointer;
            height: 100%;
            position:absolute;
            top: 0;
            right: 0;
            opacity: 0;
            -moz-opacity: 0;
            filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0)
        }

        .form-input-file-btn-back, .form-input-file-label, .form-input-file-image-new{
            display: none;
        }

        .progress.active .progress-bar {
            -webkit-transition: none !important;
            transition: none !important;
        }

        .loading{
            display: none;
        }

    </style>


    <script>

        $(document).ready(function() {

            $(".tagging").select2({
                theme: "bootstrap",
                minimumInputLength: 3,
                maximumSelectionLength: {{ (int)siteSettings('tagsLimit') }},
                tags: true,
                tokenSeparators: [","]
            })


            $('#mainForm').submit(function(e) {
                e.preventDefault();
                var $this = $(this);

                $(this).ajaxSubmit({
                    url: '{{ route('admin.products.edit',['id' => $product->id]) }}',
                    type: 'post',
                    dataType: 'json',
                    beforeSubmit: function() {
                        console.log('beforeSubmit');
                        $(".progress").show();
                        $this.find('button, input[type=submit]').prop('disabled',true);
                        $this.find('.loading').show();
                    },
                    uploadProgress: function (event, position, total, percentComplete){
                        $(".progress-bar").animate({width: percentComplete + '%'},50).html(percentComplete + '%');
                    },
                    success:function (data){
                        // reb corre con el response 200 a 300 si esta el dataType json llega con los datos, sino hay que buscar if200 en el complete que corre siempre
                        console.log('success');
                        console.log(data);
                        toastr["success"](data);
                    },
                    complete:function (data){ // corre siempre cuando termina, por error o success
                        // console.log('complete');
                        $this.find('button, input[type=submit]').prop('disabled',false);
                        $this.find('.loading').hide();
                        $(".progress").hide();
                    },
                    error: function(data){
                        console.log('error');
                        if(data.status==422){
                            var errors = data.responseJSON;
                            $.each(errors, function(index, value) {
                                toastr["error"](value);
                            });
                        }
                        else{
                            toastr["error"]('error al enviar el formulario');
                            console.log(data.status);
                            console.log(data.responseText);
                        }

                    }

                });
                return false;

            });


            $('#btn_delete').on('click',function() {

                BootstrapDialog.show({
                    message: 'Confirm Delete?',
                    buttons: [{
                        label: 'Confirm',
                        cssClass: 'btn-primary',
                        action: function(){
                            $('form[name="delete"]').submit();
                        }
                    }, {
                        label: 'Close',
                        action: function(dlg){
                            dlg.close();
                        }
                    }]
                });

            });


            $('#mainForm .form-input-file input[type="file"]').change(function(){
                var el = $(this)[0];
                var $container = $(this).closest('.form-input-file');
                if (el.files && el.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $container.find('.form-input-file-label').html(el.files[0].name).show();
                        $container.find('.form-input-file-image-original').hide();
                        $container.find('.form-input-file-image-new').attr('src', e.target.result).show();
                        $container.find('.form-input-file-btn-back').show();
                    }
                    reader.readAsDataURL(el.files[0]);
                }
            });

            $('#mainForm .form-input-file button.form-input-file-btn-change').on('click',function(){
                $(this).closest('.form-input-file').find('input[type="file"]').trigger('click');
            });

            $('#mainForm .form-input-file button.form-input-file-btn-back').on('click',function(){
                var $container = $(this).closest('.form-input-file');
                $(this).hide();
                $container.find('input[type="file"]').val('');
                $container.find('.form-input-file-label').html("").hide();
                $container.find('.form-input-file-image-new').attr('src', '').hide();
                $container.find('.form-input-file-image-original').show();
            });


        });

    </script>

@endsection