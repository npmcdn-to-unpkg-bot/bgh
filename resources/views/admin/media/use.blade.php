@extends('admin.master.index')
@section('content')

    <div class="row">

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
                <li class="list-group-item"><strong>Extensión</strong> {{ $media->extension }}</li>
                <li class="list-group-item"><strong>mime_type</strong> {{ $media->info->mime_type }}</li>
                <li class="list-group-item"><strong>original</strong> {{ $media->info->original }}</li>
                <li class="list-group-item"><strong>width</strong> {{ $media->info->width }}</li>
                <li class="list-group-item"><strong>height</strong> {{ $media->info->height }}</li>
                <li class="list-group-item"><strong>orientation</strong> {{ $media->info->orientation }}</li>
                <li class="list-group-item"><strong>resolution</strong> {{ $media->info->resolution }}</li>
                <a href="#" class="list-group-item disabled">
                    EXIF
                </a>
                <li class="list-group-item"><strong>focal_length</strong> {{ $media->info->focal_length }}</li>
                <li class="list-group-item"><strong>iso</strong> {{ $media->info->iso }}</li>
                <li class="list-group-item"><strong>shutter_speed</strong> {{ $media->info->shutter_speed }}</li>
                <li class="list-group-item"><strong>aperture</strong> {{ $media->info->aperture }}</li>
                <li class="list-group-item"><strong>copyright</strong> {{ $media->info->copyright }}</li>
                <li class="list-group-item"><strong>software</strong> {{ $media->info->software }}</li>
                <li class="list-group-item"><strong>taken_at</strong> {{ $media->info->taken_at }}</li>

            </ul>

        </div>
        <div class="col-md-9">

            <div class="box">
                <div class="box-body">


                    @if($media->type=='image')

                        <div class="row">
                            <div class="col-md-3">
                                <img class="form-input-file-image-original" src="{{ Resize::img($media->name,'listingMedia') }}" style="width:100%"/>
                            </div>
                            <div class="col-md-9">
                                <input type="text" value="{{ Resize::img($media->name,'listingMedia') }}" style="width:100%" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <img class="form-input-file-image-original" src="{{ Resize::img($media->name,'featuredMedia') }}" style="width:100%"/>
                            </div>
                            <div class="col-md-9">
                                <input type="text" value="{{ Resize::img($media->name,'featuredMedia') }}" style="width:100%" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <img class="form-input-file-image-original" src="{{ Resize::img($media->name,'mainMedia') }}" style="width:100%"/>
                            </div>
                            <div class="col-md-9">
                                <input type="text" value="{{ Resize::img($media->name,'mainMedia') }}" style="width:100%" />
                            </div>
                        </div>


                    @endif


                </div>
            </div>

        </div>


    </div>

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

            $(".pin").pin({padding: {top: 10, bottom: 10}});


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




        });

    </script>

@endsection