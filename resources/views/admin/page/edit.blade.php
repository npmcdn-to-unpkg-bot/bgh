@extends('admin.master.index')
@section('content')

    {!! HTML::style('static/admin/plugins/summernote/summernote.css') !!}
    {!! HTML::script('static/admin/plugins/summernote/summernote.js') !!}


    {!! HTML::script('static/admin/plugins/Trumbowyg-master/dist/trumbowyg.min.js') !!}

    {!! HTML::script('static/admin/plugins/Trumbowyg-master/dist/plugins/base64/trumbowyg.base64.min.js') !!}
    {!! HTML::script('static/admin/plugins/Trumbowyg-master/dist/plugins/colors/trumbowyg.colors.min.js') !!}
    {!! HTML::script('static/admin/plugins/Trumbowyg-master/dist/plugins/noembed/trumbowyg.noembed.min.js') !!}
    {!! HTML::script('static/admin/plugins/Trumbowyg-master/dist/plugins/pasteimage/trumbowyg.pasteimage.min.js') !!}
    {!! HTML::script('static/admin/plugins/Trumbowyg-master/dist/plugins/preformatted/trumbowyg.preformatted.min.js') !!}
    {!! HTML::script('static/admin/plugins/Trumbowyg-master/dist/plugins/upload/trumbowyg.upload.min.js') !!}

    {!! HTML::style('static/admin/plugins/Trumbowyg-master/dist/ui/trumbowyg.css') !!}
    {!! HTML::script('static/admin/plugins/Trumbowyg-master/dist/langs/es_ar.min.js') !!}

    <!-- Ckeditor js -->
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/ckeditor.js"></script-->

    {!! HTML::style('static/plugins/grid-editor/dist/grideditor.css') !!}
    {!! HTML::script('static/plugins/grid-editor/dist/jquery.grideditor.min.js') !!}

    <style>

        .progress.active .progress-bar {
            -webkit-transition: none !important;
            transition: none !important;
        }

        .loading{
            display: none;
        }

    </style>

    {!! Form::open(['method' => 'PATCH', 'files' => true, 'id' => 'mainForm']) !!}

    <div class="row">

        <div class="col-md-9">

            <div class="form-group">
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', $page->title, ['class' => 'form-control', 'placeholder' => 'Title of Product']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('slug', 'Slug') !!}
                {!! Form::text('slug', $page->slug, ['class' => 'form-control', 'placeholder' => 'Slug']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('tags', 'Tags') !!}
                <select class="form-control input-lg tagging" multiple="multiple" name="tags[]">
                    @foreach(explode(',',$page->tags) as $tag)
                        @if($tag)
                            <option selected="selected">{{ $tag }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div id="myGrid">
                <?php echo $page->html ?>
            </div>

        </div>
        <div class="col-md-3">

            <div class="box">
                <div class="box-body">

                    <div class="form-group">
                        {!! Form::label('profile', t('Profile')) !!}
                        {!! Form::select('profile', $profiles, $page->profile->id,['class' => 'form-control']); !!}
                    </div>

                    {!! Form::button('Update', ['class' => 'btn btn-success', 'id' => 'btn_submit']) !!}
                    {!! Form::button('Delete', ['class' => 'btn btn-danger', 'id' => 'btn_delete']) !!}

                    <i class="fa fa-cog fa-spin fa-fw loading fa-2x"></i>
                    <div class="progress progress-striped active" style="display:none;">
                        <div class="progress-bar progress-bar-success" style="width:0%"></div>
                    </div>

                </div>
            </div>

        </div>

    </div>


    <div class="row">

        <div class="col-md-12">

            <div id="editor">
                <h2>Título de la página.</h2>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus, aliquam, minima fugiat placeat provident optio nam reiciendis eius beatae quibusdam!
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus, aliquam, minima fugiat placeat provident optio nam reiciendis eius beatae quibusdam!
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus, aliquam, minima fugiat placeat provident optio nam reiciendis eius beatae quibusdam!
                </p>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus, aliquam, minima fugiat placeat provident optio nam reiciendis eius beatae quibusdam!
                </p>
            </div>

        </div>

    </div>

    {!! Form::close() !!}


    {{ Form::open(['method' => 'DELETE', 'route' => ['admin.pages.edit', $page->id], 'name' => 'delete']) }}
    {{ Form::close() }}


@endsection

@section('extra-js')

    <script>


        $.trumbowyg.svgPath = '{{ url('/static/admin/plugins/Trumbowyg-master/dist/ui/icons.svg') }}';



        $(document).ready(function() {

            $('#editor').trumbowyg({
                lang: 'es_ar',
                btnsDef: {
                    // Customizables dropdowns
                    image: {
                        dropdown: ['insertImage', 'upload', 'base64', 'noEmbed'],
                        ico: 'insertImage'
                    }
                },
                btns: [
                    ['viewHTML'],
                    ['undo', 'redo'],
                    ['formatting'],
                    'btnGrp-design',
                    ['link'],
                    ['image'],
                    'btnGrp-justify',
                    'btnGrp-lists',
                    ['foreColor', 'backColor'],
                    ['preformatted'],
                    ['horizontalRule'],
                    ['fullscreen']
                ],
                plugins: {
                    // Add imagur parameters to upload plugin
                    upload: {
                        serverPath: 'https://api.imgur.com/3/image',
                        fileFieldName: 'image',
                        headers: {
                            'Authorization': 'Client-ID 9e57cb1c4791cea'
                        },
                        urlPropertyName: 'data.link'
                    }
                }
            });


// {
//     "data": {
//         "id": "ERRruQW",
//         "title": null,
//         "description": null,
//         "datetime": 1469719583,
//         "type": "image\/jpeg",
//         "animated": false,
//         "width": 528,
//         "height": 960,
//         "size": 86375,
//         "views": 0,
//         "bandwidth": 0,
//         "vote": null,
//         "favorite": false,
//         "nsfw": null,
//         "section": null,
//         "account_url": null,
//         "account_id": 0,
//         "in_gallery": false,
//         "deletehash": "akcCj3QjdOqHg2N",
//         "name": "",
//         "link": "http:\/\/i.imgur.com\/ERRruQW.jpg",
//         "is_ad": false
//     },
//     "success": true,
//     "status": 200
// }


            $('#myGrid').gridEditor({
                new_row_layouts: [[12], [6,6], [9,3], [4,4,4]],
                valid_col_sizes: [3, 4, 6, 9, 12],
                content_types: ['summernote'],
                 row_tools: [{
                    title: 'Set background image',
                    iconClass: 'glyphicon-picture',
                    on: {
                        click: function() {
                            $(this).closest('.row').css('background-image', 'url(http://placekitten.com/g/300/300)');
                        }
                    }
                }],
                col_tools: [{
                    title: 'Set background2 image',
                    iconClass: 'glyphicon-picture',
                    on: {
                        click: function() {
                            $(this).closest('.column').css({
                                'background-image': 'url(http://placekitten.com/g/300/300)',
                                'background-size': 'cover',
                            });
                        }
                    }
                }],
            });



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
                    url: '{{ route('admin.pages.edit',['id' => $page->id]) }}',
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



            $('#btn_submit').on('click',function() {

                var html = $('#myGrid').gridEditor('getHtml');


                $('<input>').attr({
                    type: 'hidden',
                    id: 'html',
                    name: 'html'
                }).appendTo('#mainForm').val(html);


                $('#mainForm').submit();

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



        });

    </script>


@endsection