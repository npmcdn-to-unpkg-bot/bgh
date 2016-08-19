@extends('admin.master.index')
@section('content')

    <div class="row">
    {!! Form::open(['method' => 'PATCH', 'files' => true, 'id' => 'mainForm']) !!}

        <div class="col-md-3">

            <a href="{{ route('media',['id' => $media->id, 'slug' => $media->slug]) }}" target="_blank"><img src="{{ Resize::img($media->thumbnail,'featuredMedia') }}" class="thumbnail img-responsive"></a>
            <div class="form-group">
                {{-- <button type="button" class="btn btn-danger clearMediaCache" data-media="{{ $media->id }}"><i class="ion ion-nuclear"></i> Clear Cache</button> --}}
            </div>

            <ul class="list-group pin">
                <a href="#" class="list-group-item disabled">
                    Information
                </a>
                <li class="list-group-item"><strong>Type</strong> {{ $media->type }}</li>
                <li class="list-group-item"><strong>Extensión</strong> {{ strtoupper($media->extension) }}</li>
                <li class="list-group-item"><strong>Mime Type</strong> {{ $media->info->mime_type }}</li>
                <li class="list-group-item"><strong>Original</strong> {{ $media->info->original }}</li>
                <li class="list-group-item"><strong>Width</strong> {{ $media->info->width }}px</li>
                <li class="list-group-item"><strong>Height</strong> {{ $media->info->height }}px</li>
                <li class="list-group-item"><strong>Orientation</strong> {{ ucfirst($media->info->orientation) }}</li>
                <a href="#" class="list-group-item disabled">
                    Extract
                </a>
                <li class="list-group-item"><strong>Primary Color</strong> #{{ $media->info->color_1 }} <div style="height:15px;width:100%;background-color:#{{ $media->info->color_1 }}"></div></li>
                <li class="list-group-item"><strong>Secondary Color</strong> #{{ $media->info->color_2 }} <div style="height:15px;width:100%;background-color:#{{ $media->info->color_2 }}"></div></li>
                <li class="list-group-item"><strong>Overall Brightness</strong> {{ $media->info->brightness_factor }}</li>
                <a href="#" class="list-group-item disabled">
                    Camera
                </a>
                <li class="list-group-item"><strong>Resolution</strong> {{ $media->info->resolution }}</li>
                <li class="list-group-item"><strong>Camera</strong> {{ $media->info->camera }}</li>
                <li class="list-group-item"><strong>Software</strong> {{ $media->info->software }}</li>
                <li class="list-group-item"><strong>Copyright</strong> {{ $media->info->copyright }}</li>
                <a href="#" class="list-group-item disabled">
                    EXIF
                </a>
                <li class="list-group-item"><strong>Focal Length</strong> {{ $media->info->focal_length }}</li>
                <li class="list-group-item"><strong>ISO</strong> {{ $media->info->iso }}</li>
                <li class="list-group-item"><strong>Shutter Speed</strong> {{ $media->info->shutter_speed }}</li>
                <li class="list-group-item"><strong>Aperture</strong> {{ $media->info->aperture }}</li>
                <li class="list-group-item"><strong>Taken</strong> {{ $media->info->taken_at }}</li>
            </ul>

        </div>
        <div class="col-md-6">

            <div class="box">
                <div class="box-body">

                    <div class="form-group">
                        {!! Form::label('title', 'Title') !!}
                        {!! Form::text('title', $media->title, ['class' => 'form-control', 'placeholder' => 'Title of Media']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('slug', 'Slug') !!}
                        {!! Form::text('slug', $media->slug, ['class' => 'form-control', 'placeholder' => 'Slug']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::textarea('description', $media->description, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
                    </div>

                  {{--   <div class="form-group form-input-file">
                        {!! Form::label('main', 'main') !!}
                        <div class="form-input-file-hide">
                            {!! Form::file('name') !!}
                        </div>
                        <img class="form-input-file-image-original" src="{{ Resize::img($media->thumbnail,'featuredMedia') }}"  width="280"/>
                        <img class="form-input-file-image-new" src=""  width="280"/>
                        <span class="form-input-file-label"></span>
                        <button type="button" class="btn btn-default form-input-file-btn-change"><i class="fa fa-folder-open"></i></button>
                        <button type="button" class="btn btn-default form-input-file-btn-back"><i class="fa fa-close"></i></button>
                    </div> --}}

                    @if($media->type!='image')
                        <div class="form-group form-input-file">
                            {!! Form::label('thumbnail', 'thumbnail') !!}
                            <div class="form-input-file-hide">
                                {!! Form::file('thumbnail') !!}
                            </div>
                            <img class="form-input-file-image-original" src="{{ Resize::img($media->thumbnail,'featuredMedia') }}"  width="280"/>
                            <img class="form-input-file-image-new" src=""  width="280"/>
                            <span class="form-input-file-label"></span>
                            <button type="button" class="btn btn-default form-input-file-btn-change"><i class="fa fa-folder-open"></i></button>
                            <button type="button" class="btn btn-default form-input-file-btn-back"><i class="fa fa-close"></i></button>
                        </div>
                    @endif

                    <div class="form-group">
                        {!! Form::label('tags', 'Tags') !!}
                        <select class="form-control  tagging" multiple="multiple" name="tags[]">
                            @foreach(explode(',',$media->tags) as $tag)
                                @if($tag)
                                    <option selected="selected">{{ $tag }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-md-3">

            <div class="box pin">
                <div class="box-header">
                    sdsdsdaad
                </div>
                <div class="box-body">

                    <div class="form-group">
                        {!! Form::label('published', t('Published')) !!}
                        {!! Form::select('published',['1' => t('Yes'), '0' => t('No')],$media->published,['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('profile', t('Profile')) !!}
                        {!! Form::select('profile', $profiles, $media->profile->id,['class' => 'form-control']); !!}
                    </div>

                    <ul class="list-group">
                        <li class="list-group-item"><strong>Creator</strong> {{ $media->user->fullname }}</li>
                        <li class="list-group-item"><strong>Created</strong> {{ $media->created_at->diffForHumans() }} </li>
                        <li class="list-group-item"><strong>Last Updated</strong> {{ $media->updated_at->diffForHumans() }} </li>
                    </ul>

                    <div class="form-group">
                        {!! Form::submit(t('Save'), ['class' => 'btn btn-success']) !!}
                        {!! Form::button(t('Delete'), ['class' => 'btn btn-danger', 'id' => 'btn_delete']) !!}
                    </div>

                    <div class="form-group">
                        {{-- <i class="fa fa-cog fa-spin fa-fw loading fa-2x"></i> --}}
                        <div class="progress progress-striped active" style="display:none;">
                            <div class="progress-bar progress-bar-success" style="width:0%"></div>
                        </div>
                    </div>



                </div>
            </div>




        </div>

    {!! Form::close() !!}
    </div>

    {{ Form::open(['method' => 'DELETE', 'route' => ['admin.media.edit', $media->id], 'name' => 'delete']) }}
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
                    url: '{{ route('admin.media.edit',['id' => $media->id]) }}',
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